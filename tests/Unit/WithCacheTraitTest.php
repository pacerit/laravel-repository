<?php

namespace PacerIT\LaravelRepository\Tests\Unit;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use PacerIT\LaravelRepository\Tests\Resources\Entities\Test;
use PacerIT\LaravelRepository\Tests\Resources\Repositories\CachedTestRepository;

/**
 * Class WithCacheTraitTest
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 08/06/2020
 */
class WithCacheTraitTest extends AbstractTest
{
    /**
     * Test all() function.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testAllFunction()
    {
        $entity = factory(Test::class)->create();

        $entities = $this->cachedTestRepository->all();

        $this->assertInstanceOf(Collection::class, $entities);
        $this->assertTrue($entities->isNotEmpty());

        $first = $entities->first();

        $this->assertEquals($entity->toArray(), $first->toArray());

        $tag = class_basename(CachedTestRepository::class).'_0';

        echo Cache::tags([$tag])->get('*');

//        print_r($commandKeys);

//        $this->assertTrue(
//            Cache::get('all-*')
//        );
    }

//    /**
//     * Test get() function.
//     *
//     * @author Wiktor Pacer <kontakt@pacerit.pl>
//     *
//     * @since 08/06/2020
//     */
//    public function testGetFunction()
//    {
//        $entity = factory(Test::class)->create();
//
//        $entities = $this->testRepository->get();
//
//        $this->assertInstanceOf(Collection::class, $entities);
//        $this->assertTrue($entities->isNotEmpty());
//
//        $first = $entities->first();
//
//        $this->assertEquals($entity->toArray(), $first->toArray());
//    }
//
//    /**
//     * Test first() function.
//     *
//     * @author Wiktor Pacer <kontakt@pacerit.pl>
//     *
//     * @since 08/06/2020
//     */
//    public function testFirstFunctionWhenDatabaseIsEmpty()
//    {
//        $entity = $this->testRepository->first();
//
//        $this->assertNull($entity);
//    }
//
//    /**
//     * Test first() function.
//     *
//     * @author Wiktor Pacer <kontakt@pacerit.pl>
//     *
//     * @since 08/06/2020
//     */
//    public function testFirstFunction()
//    {
//        $firstEntity = factory(Test::class)->create();
//
//        // Second entity.
//        factory(Test::class)->create();
//
//        $entity = $this->testRepository->first();
//
//        $this->assertEquals($firstEntity->toArray(), $entity->toArray());
//    }
}
