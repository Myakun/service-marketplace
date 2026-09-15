<?php

declare(strict_types=1);

namespace app\tests\integration\modules\admin;

use app\modules\admin\models\category\Index as CategoryList;
use app\modules\admin\models\customer\Index as CustomerList;
use app\modules\admin\models\order\Index as OrderList;
use app\modules\admin\models\partner\Index as PartnerList;
use app\modules\admin\models\service\Index as ServiceList;
use app\modules\admin\models\user\AdminUserList;
use app\tests\support\DatabaseTestCase;
use app\tests\support\Factory;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Every admin list eager-loads relations; a relation that does not exist only fails at query time.
 */
final class ListQueriesTest extends DatabaseTestCase
{
    /**
     * @param class-string<object> $listModel
     */
    #[DataProvider('listModels')]
    public function testListQueryRunsAgainstPopulatedTables(string $listModel): void
    {
        $partner = Factory::partner();
        $service = Factory::service();
        Factory::order(['partner_id' => $partner->id, 'service_id' => $service->id]);

        /** @var object{getQuery: callable} $list */
        $list = new $listModel();
        $rows = $list->getQuery()->all();

        self::assertNotEmpty($rows);
    }

    /**
     * @return iterable<string, array{class-string<object>}>
     */
    public static function listModels(): iterable
    {
        yield 'categories' => [CategoryList::class];
        yield 'customers' => [CustomerList::class];
        yield 'orders' => [OrderList::class];
        yield 'partners' => [PartnerList::class];
        yield 'services' => [ServiceList::class];
        yield 'users' => [AdminUserList::class];
    }
}
