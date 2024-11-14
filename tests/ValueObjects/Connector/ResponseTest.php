<?php

declare(strict_types=1);

use Bluesky\ValueObjects\Connector\Response;

describe('Response', function (): void {
    it('creates from empty array', function (): void {
        // Arrange
        $data = [];

        // Act
        $response = Response::from($data);

        // Assert
        expect($response)->toBeInstanceOf(Response::class)
            ->and($response->data())->toBe([]);
    });

    describe('simple data types', function (): void {
        it('handles associative array data', function (): void {
            // Arrange
            $data = [
                'name' => 'John Doe',
                'age' => 30,
                'active' => true,
            ];

            // Act
            $response = Response::from($data);

            // Assert
            expect($response->data())->toBe($data)
                ->and($response->data())->toBeArray()
                ->and($response->data())->toHaveKeys(['name', 'age', 'active']);
        });

        it('handles sequential array data', function (): void {
            // Arrange
            $data = ['one', 'two', 'three'];

            // Act
            $response = Response::from($data);

            // Assert
            expect($response->data())->toBe($data)
                ->and($response->data())->toBeArray()
                ->and($response->data())->toHaveLength(3);
        });

        it('handles mixed array data', function (): void {
            // Arrange
            $data = [
                'items' => ['a', 'b', 'c'],
                'total' => 3,
            ];

            // Act
            $response = Response::from($data);

            // Assert
            expect($response->data())->toBe($data)
                ->and($response->data()['items'])->toBeArray()
                ->and($response->data()['total'])->toBeInt();
        });
    });

    describe('complex data structures', function (): void {
        it('handles nested arrays', function (): void {
            // Arrange
            $data = [
                'user' => [
                    'profile' => [
                        'name' => 'John Doe',
                        'settings' => [
                            'theme' => 'dark',
                            'notifications' => true,
                        ],
                    ],
                ],
            ];

            // Act
            $response = Response::from($data);

            // Assert
            expect($response->data())->toBe($data)
                ->and($response->data()['user'])->toBeArray()
                ->and($response->data()['user']['profile'])->toBeArray()
                ->and($response->data()['user']['profile']['settings'])->toBeArray();
        });

        it('handles array of objects', function (): void {
            // Arrange
            $data = [
                'users' => [
                    ['id' => 1, 'name' => 'John'],
                    ['id' => 2, 'name' => 'Jane'],
                ],
                'meta' => [
                    'total' => 2,
                    'page' => 1,
                ],
            ];

            // Act
            $response = Response::from($data);

            // Assert
            expect($response->data())->toBe($data)
                ->and($response->data()['users'])->toBeArray()
                ->and($response->data()['users'][0])->toBeArray()
                ->and($response->data()['meta'])->toBeArray();
        });
    });

    describe('immutability', function (): void {
        it('maintains data integrity', function (): void {
            // Arrange
            $originalData = ['key' => 'value'];
            $response = Response::from($originalData);

            // Act - Attempt to modify original data
            $originalData['key'] = 'modified';

            // Assert
            expect($response->data())->toBe(['key' => 'value'])
                ->and($response->data())->not->toBe($originalData);
        });
    });

    describe('edge cases', function (): void {
        it('handles empty nested structures', function (): void {
            // Arrange
            $data = [
                'empty_array' => [],
                'empty_object' => new stdClass,
                'nested_empty' => [
                    'empty' => [],
                ],
            ];

            // Act
            $response = Response::from($data);

            // Assert
            expect($response->data()['empty_array'])->toBeArray()
                ->and($response->data()['empty_array'])->toBeEmpty()
                ->and($response->data()['nested_empty']['empty'])->toBeEmpty();
        });

        it('handles data with special characters', function (): void {
            // Arrange
            $data = [
                'special' => '!@#$%^&*()',
                'unicode' => '你好',
                'emoji' => '👋🌎',
            ];

            // Act
            $response = Response::from($data);

            // Assert
            expect($response->data())->toBe($data)
                ->and($response->data()['special'])->toBe('!@#$%^&*()')
                ->and($response->data()['unicode'])->toBe('你好')
                ->and($response->data()['emoji'])->toBe('👋🌎');
        });

        it('handles numeric keys', function (): void {
            // Arrange
            $data = [
                0 => 'zero',
                1 => 'one',
                '2' => 'two',
            ];

            // Act
            $response = Response::from($data);

            // Assert
            expect($response->data())->toBe($data)
                ->and($response->data()[0])->toBe('zero')
                ->and($response->data()[1])->toBe('one')
                ->and($response->data()['2'])->toBe('two');
        });
    });
});
