<?php

declare(strict_types=1);

use Bluesky\ValueObjects\Connector\QueryParams;

covers(QueryParams::class);

describe(QueryParams::class, function (): void {
    it('creates empty query params by default', function (): void {
        // Arrange & Act
        $params = QueryParams::create();

        // Assert
        expect($params->toArray())->toBe([])
            ->and($params)->toBeInstanceOf(QueryParams::class);
    });

    describe('string parameters', function (): void {
        it('adds string parameter correctly', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params->withParam('filter', 'active');

            // Assert
            expect($result->toArray())->toBe([
                'filter' => 'active',
            ]);
        });

        it('handles empty string parameter', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params->withParam('empty', '');

            // Assert
            expect($result->toArray())->toBe([
                'empty' => '',
            ]);
        });

        it('handles special characters in string parameter', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params->withParam('search', 'hello world!@#$');

            // Assert
            expect($result->toArray())->toBe([
                'search' => 'hello world!@#$',
            ]);
        });
    });

    describe('integer parameters', function (): void {
        it('adds integer parameter correctly', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params->withParam('page', 1);

            // Assert
            expect($result->toArray())->toBe([
                'page' => 1,
            ])
                ->and($result->toArray()['page'])->toBeInt();
        });

        it('handles zero as integer parameter', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params->withParam('offset', 0);

            // Assert
            expect($result->toArray())->toBe([
                'offset' => 0,
            ])
                ->and($result->toArray()['offset'])->toBeInt();
        });

        it('handles negative integer parameter', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params->withParam('days', -7);

            // Assert
            expect($result->toArray())->toBe([
                'days' => -7,
            ])
                ->and($result->toArray()['days'])->toBeInt();
        });
    });

    describe('multiple parameters', function (): void {
        it('preserves existing parameters when adding new ones', function (): void {
            // Arrange
            $params = QueryParams::create()
                ->withParam('sort', 'desc');

            // Act
            $result = $params->withParam('page', 1);

            // Assert
            expect($result->toArray())->toBe([
                'sort' => 'desc',
                'page' => 1,
            ]);
        });

        it('handles multiple parameters of different types', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params
                ->withParam('page', 1)
                ->withParam('limit', 10)
                ->withParam('sort', 'desc')
                ->withParam('filter', 'active');

            // Assert
            expect($result->toArray())->toBe([
                'page' => 1,
                'limit' => 10,
                'sort' => 'desc',
                'filter' => 'active',
            ]);
        });

        it('overrides parameters when added multiple times', function (): void {
            // Arrange
            $params = QueryParams::create()
                ->withParam('page', 1);

            // Act
            $result = $params->withParam('page', 2);

            // Assert
            expect($result->toArray())->toBe([
                'page' => 2,
            ]);
        });
    });

    describe('immutability', function (): void {
        it('creates new instance for each modification', function (): void {
            // Arrange
            $original = QueryParams::create();

            // Act
            $withString = $original->withParam('filter', 'active');
            $withInt = $original->withParam('page', 1);

            // Assert
            expect($original)->not->toBe($withString)
                ->and($original)->not->toBe($withInt);
        });

        it('maintains independent copies', function (): void {
            // Arrange
            $first = QueryParams::create()->withParam('page', 1);

            // Act
            $second = $first->withParam('limit', 10);

            // Assert
            expect($first->toArray())->toBe(['page' => 1])
                ->and($second->toArray())->toBe([
                    'page' => 1,
                    'limit' => 10,
                ]);
        });
    });

    describe('edge cases', function (): void {
        it('handles parameters with numeric-like strings', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params->withParam('id', '123');

            // Assert
            expect($result->toArray())->toBe([
                'id' => '123',
            ])
                ->and($result->toArray()['id'])->toBeString();
        });

        it('handles parameters with boolean-like strings', function (): void {
            // Arrange
            $params = QueryParams::create();

            // Act
            $result = $params
                ->withParam('enabled', 'true')
                ->withParam('disabled', 'false');

            // Assert
            expect($result->toArray())->toBe([
                'enabled' => 'true',
                'disabled' => 'false',
            ])
                ->and($result->toArray()['enabled'])->toBeString()
                ->and($result->toArray()['disabled'])->toBeString();
        });
    });
});
