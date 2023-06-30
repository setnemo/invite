<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\InviteCode;

class CodeService
{
    protected array $usedCodes;
    protected array $addedCodes;
    protected array $bookedCodes;
    protected array $deletedCodes;
    protected array $bookedAndDeletedCodes;
    protected array $allBookedCodes;

    public function __construct()
    {
        $builder                     = InviteCode::query();
        $this->addedCodes            = $builder->get()->pluck('code')->toArray();
        $this->bookedCodes           = $builder->whereNotNull('booked_at')->get()->pluck('code')->toArray();
        $this->deletedCodes          = $builder->withTrashed()->get()->pluck('code')->toArray();
        $this->bookedAndDeletedCodes = $builder->whereNotNull('booked_at')->withTrashed()->get()->pluck('code')->toArray();
        $this->allBookedCodes        = array_merge($this->bookedCodes, $this->bookedAndDeletedCodes);
        $this->usedCodes             = $builder->withTrashed()->get()->pluck('code')->toArray();
    }

    /**
     * @param array $code
     * @return string
     */
    public function getCodeCheckbox(array $code): string
    {
        $result = '';
        if (in_array($code['code'], $this->addedCodes) && empty($code['uses'])) {
            $result .= '<span class="text-primary" data-bs-placement="top" data-bs-html="true" title="Код додано до системи, дякуємо">';

        } elseif (in_array($code['code'], $this->deletedCodes) && empty($code['uses'])) {
            $result .= '<span class="text-warning" data-bs-placement="top" data-bs-html="true" title="Код раніше вже додавався до системи">';
        } elseif (in_array($code['code'], $this->addedCodes) && !empty($code['uses'])) {
            $result .= '<span class="text-danger" data-bs-placement="top" data-bs-html="true" title="Код недійсний :(">';
        } elseif (in_array($code['code'], $this->allBookedCodes) && !empty($code['uses'])) {
            $result .= '<span class="text-success" data-bs-placement="top" data-bs-html="true" title="Код використано, дякуємо!">';
        } elseif (empty($code['uses'])) {
            $result .= '<span class="text-info" data-bs-placement="top" data-bs-html="true" title="Код доступний для пожертви">';
        }
        $result .= "{$code['code']}";
        if (in_array($code['code'], $this->addedCodes) && empty($code['uses'])) {
            $result .= '<br>(код додано до системи, дякуємо)';
        } elseif (in_array($code['code'], $this->deletedCodes) && empty($code['uses'])) {
            $result .= '<br>(код було видалено з системи)';
        } elseif (in_array($code['code'], $this->addedCodes) && !empty($code['uses'])) {
            $result .= '<br>(код недійсний)';
        } elseif (in_array($code['code'], $this->allBookedCodes) && !empty($code['uses'])) {
            $result .= '<br>(код використано, дякуємо!)';
        } elseif (empty($code['uses'])) {
            $result .= '<br>(код доступний для пожертви)';
        }
        if (empty($code['uses'])) {
            $result .= '</span>';
        }

        return $result;
    }
}
