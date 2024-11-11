<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type UserSessionResponse array{did: string, handle: string, email: null|string, emailConfirmed: null|bool, emailAuthFactor: null|bool, accessJwt: string, refreshJwt: string, active: bool}
 */
final class UserSession {}
