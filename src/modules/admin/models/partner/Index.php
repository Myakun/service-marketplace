<?php

declare(strict_types=1);

namespace app\modules\admin\models\partner;

use app\components\base\FilterModel;
use app\models\Partner;
use yii\db\ActiveQuery;

class Index extends FilterModel
{
    public ?string $contactPerson = null;

    public ?string $email = null;

    public ?string $name = null;

    public ?string $phone = null;

    public function attributeLabels(): array
    {
        $labels = (new Partner())->attributeLabels();

        return [
            'contactPerson' => $labels['contact_person'],
            'email' => $labels['email'],
            'name' => $labels['name'],
            'phone' => $labels['phone'],
        ];
    }

    public function getQuery(): ActiveQuery
    {
        $query = Partner::find()
            ->with(['createdBy']);

        if (null != $this->contactPerson) {
            $this->enableFilter();
            $query->andWhere(['like', 'contact_person', $this->contactPerson]);
        }

        if (null != $this->email) {
            $this->enableFilter();
            $query->andWhere(['like', 'email', $this->email]);
        }

        if (null != $this->name) {
            $this->enableFilter();
            $query->andWhere(['like', 'name', $this->name]);
        }

        if (null != $this->phone) {
            $this->enableFilter();
            $query->andWhere(['like', 'phone', str_replace('-', '', $this->phone)]);
        }

        return $query;
    }

    public function rules(): array
    {
        return [
            ['contactPerson', 'safe'],

            ['email', 'safe'],

            ['name', 'safe'],

            ['phone', 'safe'],
        ];
    }
}