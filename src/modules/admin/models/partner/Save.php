<?php

declare(strict_types=1);

namespace app\modules\admin\models\partner;

use app\components\web\crud\Model;
use app\models\Partner;
use app\models\Producer;
use app\models\Product;
use app\models\Series;
use app\models\StorageMode;
use DateTime;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property Partner $entity
 */
class Save extends Model
{
    public ?string $contactPerson = null;

    public ?string $email = null;

    public ?string $name = null;

    public ?string $phone = null;

    public function __construct(
        protected ActiveRecord $entity,
        array $config = []
    ) {
        parent::__construct($this->entity, $config);

        /**
         * @var Partner $entity
         */

        if ($entity->getIsNewRecord()) {
            return;
        }

        $this->contactPerson = $entity->contact_person;
        $this->email = $entity->email;
        $this->name = $entity->name;
        $this->phone = Yii::$app->formatter->formatPhone($entity->phone);
    }

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

    protected function fillEntity(): void
    {
        $this->entity->setAttributes([
            'contact_person' => $this->contactPerson,
            'email' => $this->email,
            'name' => $this->name,
            'phone' => $this->phone,
        ]);

        if ($this->entity->getIsNewRecord()) {
            $this->entity->password = Yii::$app->getSecurity()->generateRandomString(Partner::PASSWORD_MIN_LENGTH);
        }
    }

    public function rules(): array
    {
        return [
            ['contactPerson', 'filter', 'filter' => 'trim'],
            ['contactPerson', 'required'],
            ['contactPerson', 'string', 'max' => Partner::CONTACT_PERSON_MAX_LENGTH],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => Partner::NAME_MAX_LENGTH],

            ['phone', 'filter', 'filter' => 'trim'],
            ['phone', 'filter', 'filter' => function() {
                return str_replace([' ', '+7', '-', '(', ')'], '', $this->phone);
            }],
            ['phone', 'required'],
            ['phone', 'string', 'length' => Partner::PHONE_LENGTH],
        ];
    }

    public function save(): void
    {
        if ($this->entity->getIsNewRecord()) {
            Partner::register($this->contactPerson, $this->email, $this->name, $this->phone);
            return;
        }

        $this->entity->save();
    }
}