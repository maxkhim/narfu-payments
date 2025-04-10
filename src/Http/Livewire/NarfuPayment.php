<?php

declare(strict_types=1);

namespace Narfu\Payments\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Narfu\Payments\Models\Payment;
use Narfu\Payments\Models\PaymentCategory;
use Narfu\Payments\Models\PaymentRecipient;
use Narfu\Payments\Paykeeper\PaykeeperClient;
use function view;

class NarfuPayment extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public const MESSAGE_CODE_SUCCESS = 200;
    public const MESSAGE_CODE_FAIL = 500;
    public const MESSAGE_CODE_WAIT = 300;

    public string $name = 'Ok';
    public string $payer = '';
    public string $reg_place = '';
    public string $dogovor = '';
    public string $pay_for = '';
    public string $add_srv = '';
    public string $conference_name = '';
    public string $amount = '';
    public string $email = '';
    public string $recipientId = '';


    public string $defaultRecipientId = '';


    public string $code = '';
    public string $title = '';

    public string $messageResult = "";
    public int $messageCode = 0;

    private ?PaymentRecipient $currentPaymentRecipient = null;
    public ?int $currentCategoryId = null;



    protected array $validationAttributes = [
        'payer' => 'ФИО плательщика полностью',
        'reg_place' => 'Адрес регистрации',
        'dogovor' => 'Номер и дата договора',
        'pay_for' => 'За кого производится платеж',
        'add_srv' => 'Наименование доп. образовательных услуг',
        'conference_name' => 'Наименование конференции (мероприятия)',
        'recipientId' => 'За что осуществляется платёж',
        'amount' => 'Сумма платежа',
        'email' => 'Электронная почта',
    ];

    protected array $rules = [
        'payer' => 'required|min:6',
        'reg_place' => 'required|min:10',
        'recipientId' => 'required',
        'dogovor' => '',
        'amount' => 'required|numeric',
        'pay_for' => 'required',
        'email' => 'required',
    ];

    public array $tabs = [];

    public ?Builder $builder = null;
    protected array $paymentsRecipients = [];

    protected $listeners = ['itemSelect'];

    public function itemSelect(string $item)
    {
        $this->recipientId = $item;
        /** @var ?PaymentRecipient $recipient */
        $recipient = PaymentRecipient::query()->find((int)$this->recipientId);
        if ($recipient) {
            $this->currentPaymentRecipient = $recipient;
            $this->amount = (string)$recipient->price;
            $this->code = (string)$recipient->code;
            $this->title = (string)$recipient->title;
        }
    }

    protected function getRules()
    {
        $category = PaymentCategory::query()
            ->find($this->currentCategoryId);

        $recipient = PaymentRecipient::query()
            ->find((int)$this->recipientId);

        $rules = $this->rules;
        if ($recipient->rules ?? null) {
            $rules = $recipient->rules;
        } elseif ($category->rules ?? null) {
            $rules = $category->rules;
        }
        return $rules;
    }


    public function doPayment()
    {
        $this->validate($this->getRules());

        try {
            $separator = "|";
            if (strpos($this->amount, ",") !== false) {
                $this->amount = str_replace(",", ".", $this->amount);
            }

            $fields = array_keys($this->validationAttributes);
            $values = [];
            $this->title = str_replace("\"", "", $this->title);
            foreach ($fields as $field) {
                $values[$field] = str_replace(
                    "\"",
                    "",
                    str_replace($separator, " ", $this->{$field})
                );
            }

            $valuesImploded =
                $this->title . $separator .
                ($this->code ?? "") . $separator .
                implode($separator, $values);


            $paykeeperClient = new PaykeeperClient();
            $cart = [];
            $checkService = [];
            $res = $paykeeperClient->info()->getToken();
            if ($token = ($res["body"]["token"] ?? "")) {
                /** @var ?PaymentRecipient $recipient */
                $recipient = PaymentRecipient::query()
                    ->find((int)$this->recipientId);

                if ($recipient) {
                    $cart[] = [
                        'name' => $recipient->title,
                        'price' => $this->amount,
                        'quantity' => 1,
                        'sum' => $this->amount,
                        'tax' => $recipient->getPaykeeperTaxTitle(),
                        'item_type' => $recipient->getPaykeeperTypeTitle(),
                    ];

                    $checkService = [
                        "cart" => json_encode($cart),
                        "user_result_callback" => route("narfu.payments-guest"),
                        "lang" => "ru",
                        "receipt_properties" => json_encode([]),
                        "service_name" => $valuesImploded
                    ];
                }

                $orderId =
                    date("ymdHi") . "-" . substr(md5((string)microtime(true)), 0, 6);
                $payment_data = [
                    "orderid" => $orderId,
                    "clientid" => $this->payer, //"Альберт Петрович Михельсон",
                    "client_phone" => $this->dogovor,
                    "client_email" => $this->email,
                    "service_name" => json_encode($checkService),
                    "pay_amount" => $this->amount,
                    "token" => $token,
                    //"cart" => json_encode($cart)
                ];
                $res = $paykeeperClient->invoice()->prepare($payment_data);
                $invoiceId = ($res["body"]["invoice_id"] ?? null);
                if ($invoiceId) {
                    $payment = new Payment([
                        "external_id" => $invoiceId,
                        "amount" => $this->amount,
                        "metadata" => $values,
                        "currency" => "RUB",
                        "order_id" => $orderId,
                        "category_id" => $this->currentCategoryId,
                        "recipient_id" => $this->recipientId,
                        "is_test" => config("narfu-payments.is_test"),
                        "gate" => "paykeeper",
                    ]);

                    if ($payment->save()) {
                        $invoiceUrl = ($res["body"]["invoice_url"]);
                        $this->redirect($invoiceUrl);
                        session()->put("currentOrderId", $orderId);
                    } else {
                        throw new \Exception("Что-то пошло не так Paykeeper");
                    }
                } else {
                    throw new \Exception("Не удалось получить ответ от Paykeeper");
                }
            }
        } catch (\Exception $exception) {
            $this->addError(
                "sentError",
                $exception->getMessage() .
                " (L: " . $exception->getLine() . " F: " . $exception->getTraceAsString() . ")"
            );
        }
    }

    public function mount(): void
    {

        $recipientId = (int)Request::capture()->get("recipient_id");
        $paymentId = Request::capture()->get("payment_id");
        if ($paymentId) {
            $paykeeperClient = new PaykeeperClient();
            $resultRequest = ($paykeeperClient->payment()->info($paymentId));
            $paymentInfo = $resultRequest["body"][0] ?? [];

            if (isset($paymentInfo["id"])) {
                if (session()->get("currentOrderId") == $paymentInfo["orderid"]) {
                    $payment = Payment::query()
                        ->where(["order_id" => $paymentInfo["orderid"]])
                        ->first();

                    $payment->is_paid = ($paymentInfo["status"] == "success");
                    $payment->payment_id = $paymentInfo["id"];
                    $payment->save();
                    if ($payment->is_paid) {
                        $this->messageCode = self::MESSAGE_CODE_SUCCESS;
                        $this->messageResult = "Платёж прошёл успешно!";
                    } else {
                        if ($paymentInfo["status"] == "failed") {
                            $this->messageCode = self::MESSAGE_CODE_FAIL;
                            $this->messageResult = "Платёж не был выполнен";
                        } else {
                            $this->messageCode = self::MESSAGE_CODE_WAIT;
                            $this->messageResult = "Состояние платежа в обработке";
                        }
                    }
                } /**/
            } else {
                //$this->redirect(route("narfu.payments-guest"));
            }
        }


        $this->currentCategoryId = (int) Request::capture()->get("item");



        if (!$this->currentCategoryId) {
            $defaultCategory = PaymentCategory::query()->orderBy("sort")->first();

            $this->currentCategoryId = $defaultCategory ? $defaultCategory->id : null;
        }

        if ($recipientId) {
            try {
                $this->defaultRecipientId = (string)$recipientId;
                $this->itemSelect((string)$recipientId);
                if ($this->currentPaymentRecipient) {
                    $this->currentCategoryId = $this->currentPaymentRecipient->category_id;
                }
            } catch (\Exception $exception) {

            }

            //dd($this->currentPaymentRecipient);
        }

        $this->tabs = PaymentCategory::query()
            ->select(["id", "title"])
            ->orderBy("sort")
            ->get()
            ->toArray();

        $this->paymentsRecipients = PaymentRecipient::query()
            ->select(["id", "title"])
            ->where(["category_id" => $this->currentCategoryId])
            ->orderBy("sort")
            ->get()
            ->toArray();
    }

    public function isMustBeFilled($field): bool
    {
        $rules = $this->getRules();
        if (isset($rules[$field])) {
            return (strpos($rules[$field], "required") !== false);
        }
        return false;
    }

    public function isMustBeDisplayed($field): bool
    {
        $rules = $this->getRules();
        return isset($rules[$field]);
    }

    public function render()
    {
        return view('narfu-payments::components.narfu-payment', ["paymentsRecipients" => $this->paymentsRecipients]);
    }
}
