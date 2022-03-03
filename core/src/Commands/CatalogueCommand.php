<?php

namespace App\Commands;

use App\Models\Vehicle;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;
use Vesp\Services\Eloquent;

class CatalogueCommand extends UserCommand
{
    protected $name = 'catalogue';
    protected $description = 'Работа с каталогом товаров';
    protected $usage = '/catalogue';
    protected $private_only = true;

    public function __construct(Telegram $telegram, ?Update $update = null)
    {
        parent::__construct($telegram, $update);
        new Eloquent();
    }

    public function execute(): ServerResponse
    {
        if ($callback = $this->getCallbackQuery()) {
            $message = $callback->getMessage();

            $data = array_slice(explode('::', $callback->getData()), 1);
            $method = array_shift($data);
            $response = $method && method_exists($this, $method)
                ? $this->$method(...$data)
                : $this->getBrands();

            if (is_array($response)) {
                $response['chat_id'] = $message->getChat()->getId();
                $response['message_id'] = $message->getMessageId();

                return Request::editMessageText($response);
            }

            return Request::sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'message_id' => $message->getMessageId(),
                'text' => 'Какая-то ошибка, увы...',
            ]);
        }

        return $this->replyToChat('', $this->getBrands());
    }

    protected function getBrands(): array
    {
        $brands = Vehicle::query()
            ->where('active', true)
            ->groupBy('brand')
            ->orderBy('brand')
            ->limit(30)
            ->pluck('brand')
            ->toArray();

        $rows = [];
        foreach ($brands as $brand) {
            $rows[] = [
                'text' => $brand,
                'callback_data' => implode('::', [$this->usage, 'getBrand', $brand]),
            ];
        }

        return [
            'text' => 'Выберите бренд',
            'reply_markup' => new InlineKeyboard(...$this->prepareKeyboard($rows)),
        ];
    }

    protected function getBrand($brand): array
    {
        $vehicles = Vehicle::query()
            ->where(['brand' => $brand, 'active' => true])
            ->orderBy('id')
            ->limit(10);

        $rows = [
            ['text' => 'Вернуться назад', 'callback_data' => $this->usage],
        ];
        /** @var Vehicle $vehicle */
        foreach ($vehicles->get() as $vehicle) {
            $rows[] = [
                'text' => $vehicle->brand . ' ' . $vehicle->model,
                'callback_data' => implode('::', [$this->usage, 'getVehicle', $vehicle->id]),
            ];
        }

        return [
            'text' => 'Выберите модель',
            'reply_markup' => new InlineKeyboard(...$this->prepareKeyboard($rows)),
        ];
    }

    protected function getVehicle($id): ?array
    {
        if (!$vehicle = Vehicle::query()->find($id)) {
            return null;
        }

        /** @var Vehicle $vehicle */
        $rows = [
            [
                'text' => 'Другие модели ' . $vehicle->brand,
                'callback_data' => implode('::', [$this->usage, 'getBrand', $vehicle->brand]),
            ],
            ['text' => 'Обратно в каталог', 'callback_data' => $this->usage],
        ];

        return [
            'text' => print_r($vehicle->toArray(), true),
            'reply_markup' => new InlineKeyboard(...$this->prepareKeyboard($rows)),
        ];
    }

    protected function prepareKeyboard($rows): array
    {
        $array = [];

        $i = 0;
        $odd = true;
        $count = count($rows);
        while ($i <= $count) {
            $hasOne = !empty($rows[$i]);
            $hasTwo = $hasOne && !empty($rows[$i + 1]);

            if ($count >= 10) {
                if ($odd && $hasTwo && !empty($rows[$i + 2])) {
                    $array[] = [$rows[$i], $rows[$i + 1], $rows[$i + 2]];
                    $odd = false;
                    $i += 3;
                } elseif ($hasTwo) {
                    $array[] = [$rows[$i], $rows[$i + 1]];
                    $odd = true;
                    $i += 2;
                } elseif ($hasOne) {
                    $array[] = [$rows[$i]];
                    ++$i;
                } else {
                    break;
                }
            } elseif ($hasTwo) {
                $array[] = [$rows[$i], $rows[$i + 1]];
                $i += 2;
            } elseif ($hasOne) {
                $array[] = [$rows[$i]];
                ++$i;
            } else {
                break;
            }
        }

        return $array;
    }
}