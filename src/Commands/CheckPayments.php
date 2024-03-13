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


    }
}
