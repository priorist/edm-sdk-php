<?php
namespace Priorist\EDM\Client;

use Iterator;
use ArrayAccess;
use Serializable;
use Countable;
use InvalidArgumentException;
use UnexpectedValueException;
use BadMethodCallException;
use Priorist\EDM\Client\Rest\EdmClient;

class Collection implements Iterator, ArrayAccess, Serializable, Countable
{
    protected int $total = 0;
    protected array $items = [];
    protected string | null $nextPageUrl = null;
    protected string | null $previousPageUrl = null;


    public function __construct(string $json = null)
    {
        if ($json !== null) {
            $this->unserialize($json);
        }
    }


    /**
     * Returns an array copy of this collection.
     *
     * @return array The array copy holding all data
     */
    public function toArray(): array
    {
        return [
            'count' => $this->total,
            'next' => $this->nextPageUrl,
            'previous' => $this->previousPageUrl,
            'results' => $this->items
        ];
    }


    /**
     * Applies raw data from an API query to the collection
     *
     * @param array $data The data retrieved by the API client.
     *
     * @return Collection This collection
     */
    protected function fromArray(array $data): Collection
    {
        $this->total = $data['count'];
        $this->items = $data['results'];

        return $this;
    }


    /**
     * Checks if the collection contains any elements.
     *
     * @return bool True if collection has at least one element
     */
    public function hasItems(): bool
    {
        return $this->count() > 0;
    }


    /**
     * Rewinds the item iterator to the first item.
     */
    public function rewind(): void
    {
        reset($this->items);
    }


    /**
     * Returns the current item of the collection.
     *
     * @return array The item
     */
    public function current(): array | null
    {
        $item = current($this->items);

        if ($item === false) {
            return null;
        }

        return $item;
    }


    /**
     * Returns the index of the current item.
     *
     * @return int Index of current item
     */
    public function key(): int
    {
        return key($this->items);
    }


    /**
     * Moves forward to the next item.
     */
    public function next(): void
    {
        next($this->items);
    }


    /**
     * Checks if the current iterator position is valid.
     *
     * @return bool True if current iterator position is valid
     */
    public function valid(): bool
    {
        return key($this->items) !== null;
    }


    /**
     * Supports array access and checks for offset existence.
     *
     * @param int $offset The offset to check
     * @return bool True if given offset exists in the collection
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists(intval($offset), $this->items);
    }


    /**
     * Supports array access and returns an item at a given offset
     *
     * @param int $offset The offset to check
     * @return array The item or null, if offset is invalid
     */
    public function offsetGet($offset): array | null
    {
        if ($this->offsetExists($offset)) {
            return $this->items[intval($offset)];
        }

        return null;
    }


    /**
     * Not supported for collections (array access)
     */
    public function offsetSet($offset, $value): void
    {
        throw new BadMethodCallException('Collections are read-only. Use Collection::toArray() to get an array copy.');
    }


    /**
     * Not supported for collections (array access)
     */
    public function offsetUnset($offset): void
    {
        throw new BadMethodCallException('Collections are read-only. Use Collection::toArray() to get an array copy.');
    }


    /**
     * Returns the JSON representation of the collection and its elements.
     *
     * @return string The JSON encoded stream
     */
    public function serialize(): string
    {
        return json_encode($this->toArray());
    }


    /**
     * Populates the collection and its elements based on a JSON string.
     *
     * @param string $json JSON string to populate collection with
     *
     * @throws UnexpectedValueException if JSON unserializes to unexpected value.
     * @throws InvalidArgumentException if JSON is not valid
     */
    public function unserialize($json): Collection
    {
        $data = EdmClient::decodeResponse($json);

        if (!isset($data['count'], $data['results'])) {
            throw new UnexpectedValueException('Invalid collection data.');
        }

        return $this->fromArray($data);
    }


    /**
     * Returns the amount of elements in the stream.
     *
     * @return int Number of elements
     */
    public function count(): int
    {
        return count($this->items);
    }
}
