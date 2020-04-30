<?php

use PHPUnit\Framework\TestCase;

use Priorist\AIS\Client\Client;
use Priorist\AIS\Client\Collection;


class CategoryTest extends TestCase
{
    public function testList()
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $categories = $client->category->findAll();

        $this->assertInstanceOf(Collection::class, $categories);
        $this->assertGreaterThanOrEqual(0, $categories->count());

        if (!$categories->hasItems()) {
            $this->markTestSkipped('No categories returned.');
        }

        foreach ($categories as $category) {
            $this->assertIsArray($category);
            $this->assertIsInt($category['id']);
        }

        $this->assertNull($categories->current());

        $categories->rewind();

        return $categories;
    }


    /**
     * @depends testList
     */
    public function testSingle(Collection $categories)
    {
        $this->assertIsArray($categories->current());
        $this->assertArrayHasKey('id', $categories->current());

        $existingCategoryId = $categories->current()['id'];

        $this->assertIsInt($existingCategoryId);

        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertNull($client->category->findById(0));

        $category = $client->category->findById($existingCategoryId);

        $this->assertIsArray($category);
        $this->assertArrayHasKey('id', $category);
        $this->assertEquals($existingCategoryId, $category['id']);

        $this->assertArrayHasKey('name', $category);

        return $category;
    }


    public function testTopLevel()
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $categories = $client->category->findTopLevel();

        $this->assertInstanceOf(Collection::class, $categories);
        $this->assertGreaterThanOrEqual(0, $categories->count());

        if (!$categories->hasItems()) {
            $this->markTestSkipped('No categories returned.');
        }

        foreach ($categories as $category) {
            $this->assertIsArray($category);
            $this->assertNull($category['parent_category']);
        }

        return $client;
    }


    /**
     * @depends testSingle
     */
    public function testChildren(array $potentialParent)
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $children = $client->category->findByParent($potentialParent['id']);

        $this->assertInstanceOf(Collection::class, $children);
        $this->assertGreaterThanOrEqual(0, $children->count());

        foreach ($children as $category) {
            $this->assertIsArray($category);
            $this->assertEquals($potentialParent['id'], $category['parent_category']);
        }
    }


    /**
     * @depends testSingle
     */
    public function testSearch(array $category)
    {
        $client = new Client(getenv('AIS_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $categories = $client->category->findBySearchPhrase($category['name']);

        $this->assertInstanceOf(Collection::class, $categories);
        $this->assertGreaterThan(0, $categories->count());
    }
}
