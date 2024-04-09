<?php

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

use Priorist\EDM\Client\Client;
use Priorist\EDM\Client\Collection;


class LecturerTest extends TestCase
{
    public function testList()
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $lecturers = $client->lecturer->findAll();

        $this->assertInstanceOf(Collection::class, $lecturers);
        $this->assertGreaterThanOrEqual(0, $lecturers->count());

        if (!$lecturers->hasItems()) {
            $this->markTestSkipped('No lecturers returned.');
        }

        foreach ($lecturers as $lecturer) {
            $this->assertIsArray($lecturer);
            $this->assertIsInt($lecturer['id']);
        }

        $this->assertNull($lecturers->current());

        $lecturers->rewind();

        return $lecturers;
    }


    #[Depends('testList')]
    public function testSingle(Collection $lecturers)
    {
        $this->assertIsArray($lecturers->current());
        $this->assertArrayHasKey('id', $lecturers->current());

        $existingLecturerId = $lecturers->current()['id'];

        $this->assertIsInt($existingLecturerId);

        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $this->assertNull($client->lecturer->findById(0));

        $lecturer = $client->lecturer->findById($existingLecturerId);

        $this->assertIsArray($lecturer);
        $this->assertArrayHasKey('id', $lecturer);
        $this->assertEquals($existingLecturerId, $lecturer['id']);

        $this->assertArrayHasKey('last_name', $lecturer);

        return $lecturer;
    }


    #[Depends('testSingle')]
    public function testSearch(array $lecturer)
    {
        $client = new Client(getenv('EDM_URL'), getenv('CLIENT_ID'), getenv('CLIENT_SECRET'));

        $lecturers = $client->lecturer->findBySearchPhrase($lecturer['last_name']);

        $this->assertInstanceOf(Collection::class, $lecturers);
        $this->assertGreaterThan(0, $lecturers->count());
    }
}
