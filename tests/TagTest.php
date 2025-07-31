<?php

declare(strict_types=1);

namespace Priorist\EDM\Test;

use PHPUnit\Framework\Attributes\Depends;
use Priorist\EDM\Client\Client;
use Priorist\EDM\Client\Collection;

class TagTest extends AbstractTestCase
{
    public function testList()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $tags = $client->tag->findAll();

        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertGreaterThanOrEqual(0, $tags->count());

        if (!$tags->hasItems()) {
            $this->markTestSkipped('No tags returned.');
        }

        foreach ($tags as $tag) {
            $this->assertIsArray($tag);
            $this->assertIsInt($tag['id']);
        }

        $this->assertNull($tags->current());

        $tags->rewind();

        return $tags;
    }


    #[Depends('testList')]
    public function testSingle(Collection $tags)
    {
        $this->assertIsArray($tags->current());
        $this->assertArrayHasKey('id', $tags->current());

        $existingTagId = $tags->current()['id'];

        $this->assertIsInt($existingTagId);

        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertNull($client->tag->findById(0));

        $tag = $client->tag->findById($existingTagId);

        $this->assertIsArray($tag);
        $this->assertArrayHasKey('id', $tag);
        $this->assertEquals($existingTagId, $tag['id']);

        $this->assertArrayHasKey('name', $tag);

        return $tag;
    }


    #[Depends('testSingle')]
    public function testSearch(array $tag)
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $tags = $client->tag->findBySearchPhrase($tag['name']);

        $this->assertInstanceOf(Collection::class, $tags);
        $this->assertGreaterThan(0, $tags->count());
    }
}
