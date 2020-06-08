<?php

namespace PacerIT\LaravelRepository\Tests\Unit;

use Illuminate\Support\Collection;
use PacerIT\LaravelRepository\Tests\Resources\Entities\Test;

/**
 * Class CoreRepositoryTest
 *
 * @author Wiktor Pacer <kontakt@pacerit.pl>
 *
 * @since 08/06/2020
 */
class CoreRepositoryTest extends AbstractTest
{

    /**
     * Test makeEntity() function.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testMakeEntityFunction()
    {
        $entity = $this->testRepository->makeEntity()->getEntity();

        $this->assertInstanceOf(Test::class, $entity);
    }

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

        $entities = $this->testRepository->all();

        $this->assertInstanceOf(Collection::class, $entities);
        $this->assertTrue($entities->isNotEmpty());

        $first = $entities->first();

        $this->assertEquals($entity->toArray(), $first->toArray());
    }

    /**
     * Test get() function.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testGetFunction()
    {
        $entity = factory(Test::class)->create();

        $entities = $this->testRepository->get();

        $this->assertInstanceOf(Collection::class, $entities);
        $this->assertTrue($entities->isNotEmpty());

        $first = $entities->first();

        $this->assertEquals($entity->toArray(), $first->toArray());
    }

    /**
     * Test first() function.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testFirstFunctionWhenDatabaseIsEmpty()
    {
        $entity = $this->testRepository->first();

        $this->assertNull($entity);
    }

    /**
     * Test first() function.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testFirstFunction()
    {
        $firstEntity = factory(Test::class)->create();

        // Second entity.
        factory(Test::class)->create();

        $entity = $this->testRepository->first();

        $this->assertEquals($firstEntity->toArray(), $entity->toArray());
    }

    /**
     * Test create() function.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testCreateFunction()
    {
        $entity = $this->testRepository
            ->makeEntity()
            ->create(
                [
                    'name' => 'example',
                ]
            );

        $this->assertInstanceOf(Test::class, $entity);

        $this->assertDatabaseHas(
            'tests',
            [
                'id' => $entity->id,
            ]
        );
    }

    /**
     * Test updateOrCreate() function, when entity not exist.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testUpdateOrCreateFunctionWhenEntityNotExist()
    {
        $this->assertDatabaseMissing(
            'tests',
            [
                'name' => 'example',
            ]
        );

        $entity = $this->testRepository
            ->makeEntity()
            ->updateOrCreate(
                [
                    'name' => 'example',
                ],
                [
                    'name' => 'example2',
                ]
            );

        $this->assertInstanceOf(Test::class, $entity);

        $this->assertDatabaseHas(
            'tests',
            [
                'name' => 'example2',
            ]
        );
    }

    /**
     * Test updateOrCreate() function, when entity exist.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testUpdateOrCreateFunctionWhenEntityExist()
    {
        $existingEntity = factory(Test::class)
            ->create(
                [
                    'name' => 'example',
                ]
            );

        $entity = $this->testRepository
            ->makeEntity()
            ->updateOrCreate(
                [
                    'name' => 'example',
                ],
                [
                    'name' => 'example2',
                ]
            );

        $this->assertInstanceOf(Test::class, $entity);

        $this->assertDatabaseHas(
            'tests',
            [
                'id'   => $existingEntity->id,
                'name' => 'example2',
            ]
        );
    }

    /**
     * Test update() function.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testUpdateFunction()
    {
        $existingEntity = factory(Test::class)
            ->create(
                [
                    'name' => 'example',
                ]
            );

        $entity = $this->testRepository
            ->makeEntity()
            ->update(
                $existingEntity->id,
                [
                    'name' => 'example2',
                ]
            );

        $this->assertInstanceOf(Test::class, $entity);

        $this->assertDatabaseHas(
            'tests',
            [
                'id'   => $existingEntity->id,
                'name' => 'example2',
            ]
        );
    }

    /**
     * Test update() function.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testDeleteFunction()
    {
        $existingEntity = factory(Test::class)
            ->create(
                [
                    'name' => 'example',
                ]
            );

        $this->testRepository
            ->makeEntity()
            ->delete(
                $existingEntity->id
            );

        $this->assertDatabaseMissing(
            'tests',
            [
                'id'   => $existingEntity->id,
            ]
        );
    }

    /**
     * Test firstOrNew() function when entity not exist.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testFirstOrNewFunctionWhenEntityNotExist()
    {
        $entity = $this->testRepository
            ->makeEntity()
            ->firstOrNew(
                [
                    'name' => 'example',
                ]
            );

        $this->assertInstanceOf(Test::class, $entity);
        $this->assertNull($entity->id);
    }

    /**
     * Test firstOrNew() function when entity exist.
     *
     * @author Wiktor Pacer <kontakt@pacerit.pl>
     *
     * @since 08/06/2020
     */
    public function testFirstOrNewFunctionWhenEntityExist()
    {
        $existingEntity = factory(Test::class)
            ->create(
                [
                    'name' => 'example',
                ]
            );

        $entity = $this->testRepository
            ->makeEntity()
            ->firstOrNew(
                [
                    'name' => 'example',
                ]
            );

        $this->assertInstanceOf(Test::class, $entity);
        $this->assertEquals($existingEntity->toArray(), $entity->toArray());
    }
}
