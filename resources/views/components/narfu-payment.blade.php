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

            <div class="grid grid-cols-3 gap-3">



            </div>

            <div class="text-right">
            <button class="border-gray-200 bg-indigo-500 text-white border rounded p-2 w-48 mt-5" value="ok" name="ok" wire:click="doPayment">
                Оплатить
            </button>
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


