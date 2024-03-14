<?php

declare(strict_types=1);

namespace Narfu\Payments\Commands;

use Exception;
use Illuminate\Console\Command;

class CheckPayments extends Command
{
    /**
     * Имя команды
     *
     * @var string
     */
    protected $signature = 'narfu:payments:check';

    /**
     * Описание команды.
     *
     * @var string
     */

    protected $description = 'Проверка настроек модуля';

    /**
     * Исполнение
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {

        /*$pivot = [];
        $lm = LicensedModule::query()->whereBetween("id", [4,15])->where("id", "<>", 11)->get();
        $lk = DeployKey::query()->whereBetween("id", [10,20])->get();
        foreach ($lm as $m) {

            $pivot[$m->id] =
                [
                    "module_name" => "".$m->name
                ];

            foreach ($lk as $k) {
                $pivot[$m->id]["key_".$k->id] =
                        ModuleDeployStatus::query()
                            ->where(["module_id"=>$m->id, "deploy_key_id"=>$k->id])
                            ->first()
                            ->is_enabled;
            }
        }

        dd(collect($pivot)->sortByDesc([
            ['key_11', 'asc'],
            ['module_name', 'desc'],
        ]));*/
        /*dd($lm->count());

*/
        /*dd(
            LicensedModule::query()
            ->with(["keys"])
            ->orderBy("title")

            ->get()
            ->toArray()
        );*/
        /*$lm = LicensedModule::query()
            ->find(13);
        foreach ($lm->keys as $key) {
            //dump($key->pivot());
            echo $key->pivot->is_enabled.": Key {$key->title} was at" .PHP_EOL;
        }
        return;
*/
        $this->alert('Проверка настроек модуля оплат');

        $yookassaClient = new YookassaClient();

        //dd($yookassaClient->payment()->list());
        dd($yookassaClient->payment()->get("2d540cea-000f-5000-a000-1233e7fd12f8"));
        return;

        //dd($yookassaClient->info()->info());

        $paymentData = [
            "amount" => [
                "value" => (rand(500, 10000).".00"),
                "currency" => "RUB"
            ],
            "payment_method_data" => [
                "type" => "bank_card",
            ],
            "confirmation" => [
                "type" => "redirect",
                "return_url" => "https://narfu.ru",
            ],
            "description" => "Заказ #".crc32(md5(microtime())),
        ];
        //dd(json_encode($paymentData));
        dump($paymentData);
        $res = $yookassaClient->payment()->create($paymentData);
        dd($res);

        return;


        $paykeeperClient = new PaykeeperClient();

        //$res = $paykeeperClient->invoice()->info("20240123145545204");
        //$res = $paykeeperClient->payment()->info("5");
        //$res = $paykeeperClient->payment()->params("5");

        //dd($res);



        //$res = $paykeeperClient->info()->getToken();
        $res = $paykeeperClient->info()->getToken();

        if ($token = ($res["body"]["token"]??"")) {
            //dd($token);
            /*$payment_data = [

                "pay_amount"    => 2732.19,
                "clientid"      => "Альберт Петрович Михельсон",
                "orderid"       => date("YmdHisB"),
                "service_name"  => "Оплата за образовательные услуги ДОУ СОВА",
                "client_email"  => "Вместо почты пусть будет номер договора",
                "client_phone"  => "Вместо телефона пусть будет 'код'",
                "token"         => $token
            ];*/

            $payment_data = [
                "orderid"       => "12.5",
                "clientid"      => "Альберт Петрович Михельсон",
                "client_phone"  => "№121432/123123  от 12.12.2012",
                "client_email"  => "За Петра Михельсона",
                "service_name"  => "Оплата за образовательные услуги ДОУ СОВА",
                "pay_amount"    => 973.00,
                "token"         => $token
            ];


            $res = $paykeeperClient->invoice()->prepare($payment_data);
            dd($res);
        }

        $this->line("");
    }
}
