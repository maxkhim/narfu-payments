<div>

    <div class="w-full p-6 bg-white overflow-hidden shadow rounded-lg mb-4 mt-2">
        <div class="flex-1 min-w-0">
            <h1 class="text-xl text-gray-700 font-semibold leading-tight">
                Оплата услуг САФУ
            </h1>
        </div>
    </div>

    <div class="w-full p-6 bg-white overflow-hidden shadow rounded-lg">

        @error('sentError')
        <div class="w-full p-6 mb-7 bg-red-100 overflow-hidden shadow rounded-lg text-red-500 text-center">
            <strong>Ошибка!</strong> {{ $message }}
        </div>
        @enderror

        @if($messageCode == \Narfu\Payments\Http\Livewire\NarfuPayment::MESSAGE_CODE_SUCCESS)
        <div class="w-full p-6 mb-7 bg-green-100 overflow-hidden shadow rounded-lg text-green-500 text-center">
            {{ $messageResult }}
        </div>
        @endif

        @if($messageCode == \Narfu\Payments\Http\Livewire\NarfuPayment::MESSAGE_CODE_FAIL)
            <div class="w-full p-6 mb-7 bg-red-100 overflow-hidden shadow rounded-lg text-red-500 text-center">
                {{ $messageResult }}
            </div>
        @endif

        @if($messageCode == \Narfu\Payments\Http\Livewire\NarfuPayment::MESSAGE_CODE_WAIT)
            <div class="w-full p-6 mb-7 bg-gray-100 overflow-hidden shadow rounded-lg text-gray-600 text-center">
                {{ $messageResult }}
            </div>
        @endif


        @foreach($tabs as $id => $tab)
            <a href="?item={{$tab["id"]}}"
               class="md:inline-block md:mr-2 inline-block px-3 py-1 leading-5 text-xs rounded-full
               text-gray-600 hover:text-gray-800 focus:outline-none mt-2 border-indigo-400 border
       focus:text-gray-800 focus:bg-gray-200 focus:bg-gray-300 font-semibold text-center
@if ( $tab["id"] == $currentCategoryId ) bg-indigo-600 text-white
@else text-indigo-700 hover:bg-gray-100 @endif
                       ">
                @if ($tab["title"])
                    {{ $tab["title"] }}
                @endif
            </a>
        @endforeach

        <div class="mt-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                <livewire:narfu-select
                        :items="$paymentsRecipients"
                        :defaultId="$defaultRecipientId"
                        label="За что осуществляется платёж"
                />
                @error("recipientId")
                <div class="text-red-600 text-size-10-standart">{{ $message }}</div>
                @enderror
                </div>
                <div>
                    <x-narfu-payments::ui.kit.input
                            name="amount"
                            label="Сумма платежа (руб.)"
                            placeholder=""
                            mustBeFilled="true"
                            hint=""></x-narfu-payments::ui.kit.input>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @if ($this->isMustBeDisplayed('payer'))
                <div>
                    <x-narfu-payments::ui.kit.input name="payer"
                                                    label="ФИО плательщика полностью"
                                                    placeholder="ФИО плательщика полностью"
                                                    hint=""
                                                    mustBeFilled="{{ $this->isMustBeFilled('payer') }}"></x-narfu-payments::ui.kit.input>
                </div>
                @endif

                <div>
                    <x-narfu-payments::ui.kit.input name="reg_place"
                                                    label="Адрес регистрации"
                                                    placeholder="Адрес регистрации"
                                                    hint=""
                                                    mustBeFilled="{{ $this->isMustBeFilled('reg_place') }}"></x-narfu-payments::ui.kit.input>
                </div>
                @if ($this->isMustBeDisplayed('dogovor'))
                <div>
                    <x-narfu-payments::ui.kit.input
                            name="dogovor"
                            label="Номер и дата договора"
                            hint="Например '№123 от 12.12.2023'"
                            mustBeFilled="{{ $this->isMustBeFilled('dogovor') }}"></x-narfu-payments::ui.kit.input>
                </div>
                @endif
{{--            </div>--}}

{{--            <div class="grid grid-cols-3 gap-3">--}}
                <div>
                    <x-narfu-payments::ui.kit.input
                            name="pay_for"
                            label="За кого производится платеж"
                            placeholder="ФИО полностью"
                            hint=""
                            mustBeFilled="{{ $this->isMustBeFilled('pay_for') }}"></x-narfu-payments::ui.kit.input>
                </div>
                @if ($this->isMustBeDisplayed('add_srv'))
                <div>
                    <x-narfu-payments::ui.kit.input
                            name="add_srv"
                            label="Наименование доп. образовательных услуг"
                            placeholder="Наименование доп. образовательных услуг"
                            mustBeFilled="{{ $this->isMustBeFilled('add_srv') }}"
                    ></x-narfu-payments::ui.kit.input>
                </div>
                @endif
                @if ($this->isMustBeDisplayed('conference_name'))
                <div>
                    <x-narfu-payments::ui.kit.input
                            name="conference_name"
                            label="Наименование"
                            placeholder="Конференции, мероприятия, услуги, книжной продукции"
                            mustBeFilled="{{ $this->isMustBeFilled('conference_name') }}"
                    ></x-narfu-payments::ui.kit.input>
                </div>
                @endif
                @if ($this->isMustBeDisplayed('email'))
                <div>
                    <x-narfu-payments::ui.kit.input
                            name="email"
                            label="Электронная почта"
                            placeholder="Например: i.ivanov@narfu.ru"
                            mustBeFilled="true"
                            hint="На этот адрес будет направлена информация об оплате"
                    ></x-narfu-payments::ui.kit.input>
                </div>
                @endif


            </div>

            <div class="grid grid-cols-1 gap-1">
                <x-narfu-payments::ui.kit.checkbox
                        name="pd_confirm"
                        id="pd_confirm"
                        label="Даю согласие на обработку своих персональных данных"
                        mustBeFilled="true"
                        hint="Даю согласие на обработку своих персональных данных"
                ></x-narfu-payments::ui.kit.checkbox>
            </div>

            <div class="text-right">
                <button class="border-gray-200 bg-indigo-500 text-white border rounded p-2 w-48 mt-5" value="ok" name="ok" wire:click="doPayment">
                    Оплатить
                </button>
            </div>

            <div class="text-left">
                <a href="https://narfu.ru/university/structure/faq/11320/395982/"
                   target="_blank" class="inline-block border-gray-200 bg-indigo-500 text-white border rounded px-5 p-2 w-auto mt-5">
                    Справка для получения налогового вычета
                </a>
            </div>

            <div class="mt-8 font-bold text-xl">
                Способы оплаты
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <div class="mt-8 font-bold text-sm">
                        Наличный расчёт
                    </div>

                    <div class="mt-8 text-xs">
                        Услуга оплачивается в кассе университета по адресу
                        г. Архангельск ул. Набережная Северной Двины д.17 2-й этаж.
                        Возможна оплата наличными и банковской картой.
                    </div>

                    <div class="mt-8 font-bold text-sm">
                        Согласие на обработку персональных данных
                    </div>

                    <div class="mt-8 text-xs">
                        <p class="text-justify leading-relaxed">
                            В соответствии с Федеральным законом от 27.07.2006 года № 152-ФЗ «О персональных данных» даю свое согласие федеральному государственному автономному учреждению высшего образования «Северный (Арктический) федеральный университет имени М.В. Ломоносова» (далее – университет, оператор), расположенному по адресу: 163000, г. Архангельск, Наб. Северной Двины, 17, ИНН 2901039102, ОГРН 1022900517793, сведения об информационных ресурсах оператора:
                            <a href="https://lk.narfu.ru/narfu/payments-guest" target="_blank" class="text-blue-600 hover:underline">https://lk.narfu.ru/narfu/payments-guest</a>,
                            на обработку своих персональных данных, которые мной предоставляются в распоряжение университета и соответствуют категориям персональных данных, указанных в разделе 8 Положения об обработке персональных данных и о сведениях относительно реализуемых требований к защите персональных данных, утвержденного приказом от 01.08.2018 № 586, в том числе:
                        </p>

                        <ul class="list-disc list-inside mt-2 space-y-1 text-justify leading-relaxed ml-5">
                            <li><strong>фамилия, имя, отчество плательщика</strong> (отчество при наличии);</li>
                            <li><strong>контактная информация</strong>, которую субъект персональных данных предоставляет самостоятельно: адрес электронной почты (e-mail);</li>
                            <li><strong>информация о платеже</strong>: оплата за обучение (включая наименование ВШ/института/колледжа); /доп.образование (включая информацию о подразделении, оказывающим платные образовательные услуги); /общежитие (включая информацию об оплачиваемом общежитии); /издательство (включая информацию о журнале); /детский сад (включая наименование и перечень услуг); /прочие услуги (по перечню на сайте);</li>
                            <li><strong>адрес регистрации плательщика</strong>, указанный субъектом самостоятельно;</li>
                            <li><strong>информация о договоре</strong> (номер и дата договора);</li>
                            <li><strong>информация о лице, за кого производится платеж</strong>;</li>
                            <li><strong>дополнительная информация</strong>, которую я самостоятельно предоставляю университету, заполнив информацию в полях формы для оплаты,</li>
                        </ul>

                        <p class="text-justify leading-relaxed mt-4">
                            путем совершения действий (операций) или совокупности действий (операций), совершаемых с использованием средств автоматизации и (или) без использования таких средств с персональными данными, включая сбор, запись, систематизацию, накопление, хранение, уточнение (обновление, изменение), извлечение, использование, блокирование, удаление, уничтожение персональных данных,
                            для достижения целей обработки персональных данных, указанных в разделе 4 Положения об обработке персональных данных и о сведениях относительно реализуемых требований к защите персональных данных, утвержденного приказом ректора от 01.08.2018 № 586, в том числе:
                        </p>

                        <ul class="list-disc list-inside mt-2 space-y-1 text-justify leading-relaxed ml-5">
                            <li><strong>оплаты услуг университета</strong> по предусмотренным разделам и типам услуг;</li>
                        </ul>

                        <p class="text-justify leading-relaxed mt-4">
                            Давая настоящее согласие, я подтверждаю, что мной получено согласие на передачу университету персональных данных лица, за которого я осуществляю оплату, для целей оплаты услуг университета по предусмотренным разделам и типам услуг, и обязуюсь предоставить подтверждение по запросу университета.
                        </p>

                        <p class="text-justify leading-relaxed mt-4">
                            Биометрические данные оператором не обрабатываются, в связи с чем согласие на обработку биометрических данных не даю.
                        </p>

                        <p class="text-justify leading-relaxed mt-4">
                            Настоящее согласие вступает в силу со дня его подписания и действует в течение 5 лет после окончания оказания услуг университетом и (или) исполнения мной или университетом обязанности по оказанию таких услуг.
                        </p>

                        <p class="text-justify leading-relaxed mt-4">
                            Настоящее согласие может быть отозвано мной в любое время на основании моего письменного заявления.
                        </p>

                        <p class="text-justify leading-relaxed mt-4">
                            В случае отзыва мною согласия на обработку персональных данных университет вправе продолжить обработку персональных данных без моего согласия при наличии оснований, указанных в пунктах 2 – 11 части 1 статьи 6, части 2 статьи 10 и части 2 статьи 11 Федерального закона от 27.06.2006 № 152-ФЗ «О персональных данных».
                        </p>

                        <p class="text-justify leading-relaxed font-medium mt-4">
                            Права и обязанности в области защиты персональных данных мне разъяснены.
                        </p>

                        <p class="text-justify leading-relaxed font-medium mt-4">
                            Я подтверждаю, что, давая настоящее согласие, я действую по своей воле и в своих интересах.
                        </p>

                        <div class="mt-6">
                            <label class="flex items-start space-x-2 cursor-pointer">
                                <span class="text-justify leading-relaxed">
                Я ознакомлен с правами и обязанностями в области защиты персональных данных на основании локальных документов, размещенных на сайте университета в разделе
                <a href="https://narfu.ru/sveden/document/" target="_blank" class="text-blue-600 hover:underline">https://narfu.ru/sveden/document/</a>
            </span>
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="mt-8 font-bold text-sm">
                        Банковской картой
                    </div>
                    <div class="mt-8 text-xs">
                        Для оплаты (ввода реквизитов Вашей карты) Вы будете перенаправлены на платёжную платформу
                        Газпромбанк (Акционерное Общество). Соединение с платёжной платформой и передача информации
                        осуществляется в защищенном режиме с использованием протокола шифрования SSL.
                        В случае если Ваш банк поддерживает технологию безопасного проведения интернет-платежей
                        Verified By Visa, MasterCard SecureCode, MIR Accept, J-Secure для проведения платежа также
                        может потребоваться ввод специального пароля.
                    </div>
                    <div class="mt-8 text-xs">
                        Безопасность платежей обеспечивается с помощью Банка-эквайера (Газпромбанк (Акционерное Общество)),
                        функционирующего на основе современных протоколов и технологий, разработанных платежными системами МИР,
                        Visa International и Mastercard Worldwide (3D-Secure: Verified by VISA, Mastercard SecureCode, MirAccept).
                        Обработка полученных конфиденциальных данных Держателя карты производится в процессинговом центре Банка,
                        сертифицированного по стандарту PCI DSS. Безопасность передаваемой информации обеспечивается с помощью
                        современных протоколов обеспечения безопасности в сети Интернет.
                    </div>
                    <div class="mt-8 text-xs">
                        Для выбора оплаты услуги или товара с помощью банковской карты необходимо выбрать нужный раздел в верхнем меню,
                        заполнить обязательные поля и нажать кнопку Оплатить.
                        Вы будете перенаправлены на защищенную платежную страницу "Газпромбанк" (Акционерное общество),
                        где будет необходимо ввести данные Вашей пластиковой карты. В случае успешной авторизации
                        Вы получите от сайта уведомление о том, что оплата проведена и/или описание порядка получения товара/услуги.
                    </div>
                </div>
            </div>


            <div class="mt-4 text-center w-full bg-white p-4 mb-7">
                <img
                        src="/narfu/payments/logo/pay-logo.svg"
                        class="text-center h-10 inline"
                />
            </div>
        </div>

    </div>


</div>


