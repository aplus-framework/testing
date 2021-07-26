<?php
/*
 * This file is part of Aplus Framework Testing Library.
 *
 * (c) Natan Felles <natanfelles@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Tests\Testing;

use Framework\HTTP\URL;
use Framework\Testing\TestCase;

class TestCaseMock extends TestCase
{
    /**
     * @param string|URL $url
     * @param string $method
     * @param array<string,string> $headers
     * @param string $body
     *
     * @return static
     */
    public function prepareRequest(
        URL | string $url,
        string $method = 'GET',
        array $headers = [],
        string $body = ''
    ) : static {
        return parent::prepareRequest($url, $method, $headers, $body);
    }
}
