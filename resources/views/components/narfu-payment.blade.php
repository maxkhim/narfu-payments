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
            <x-narfu-payments::ui.kit.tab label="{{ $tab }}" id="{{ $id }}" currentId="{{ $currentCategoryId }}" />
        @endforeach

        <div class="mt-8">
            <div class="grid grid-cols-2 gap-3">
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

            <div class="grid grid-cols-3 gap-3">
                @if ($this->isMustBeFilled('payer'))
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
                @if ($this->isMustBeFilled('dogovor'))
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
                @if ($this->isMustBeFilled('add_srv'))
                <div>
                    <x-narfu-payments::ui.kit.input
                            name="add_srv"
                            label="Наименование доп. образовательных услуг"
                            placeholder="Наименование доп. образовательных услуг"
                            mustBeFilled="{{ $this->isMustBeFilled('add_srv') }}"
                    ></x-narfu-payments::ui.kit.input>
                </div>
                @endif
                @if ($this->isMustBeFilled('conference_name'))
                <div>
                    <x-narfu-payments::ui.kit.input
                            name="conference_name"
                            label="Наименование конференции (мероприятия)"
                            placeholder="Наименование конференции (мероприятия)"
                            mustBeFilled="{{ $this->isMustBeFilled('conference_name') }}"
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
        </div>



</div>

