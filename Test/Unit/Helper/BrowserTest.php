<?php
/**
 * MageOS
 *
 * @category   MageOS
 * @package    MageOS_AdminActivityLog
 * @copyright  Copyright (C) 2026 MageOS (https://mage-os.org/)
 * @license    https://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */

declare(strict_types=1);

namespace MageOS\AdminActivityLog\Test\Unit\Helper;

use Magento\Framework\App\Helper\Context;
use MageOS\AdminActivityLog\Helper\Browser;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class BrowserTest extends TestCase
{
    private Browser $browser;

    protected function setUp(): void
    {
        $this->browser = new Browser($this->createStub(Context::class));
    }

    /**
     * User agents without the "Version/" or "CriOS/" token used to throw a TypeError.
     *
     * @dataProvider userAgentProvider
     */
    #[DataProvider('userAgentProvider')]
    public function testDetectsBrowserAndVersion(string $agent, string $browser, string $version): void
    {
        $this->browser->setUserAgent($agent);

        $this->assertSame($browser, $this->browser->getBrowser());
        $this->assertSame($version, $this->browser->getVersion());
    }

    public static function userAgentProvider(): array
    {
        return [
            'iPhone without Version token' => [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 17_7_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
                Browser::BROWSER_IPHONE,
                Browser::VERSION_UNKNOWN,
            ],
            'iPhone Safari without Version token' => [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 Safari/604.1',
                Browser::BROWSER_IPHONE,
                Browser::VERSION_UNKNOWN,
            ],
            'iPad without Version token' => [
                'Mozilla/5.0 (iPad; CPU OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
                Browser::BROWSER_IPAD,
                Browser::VERSION_UNKNOWN,
            ],
            'iPhone Safari' => [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.6 Mobile/15E148 Safari/604.1',
                Browser::BROWSER_IPHONE,
                '18.6',
            ],
            'iPhone Chrome' => [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/141.0.7390.96 Mobile/15E148 Safari/604.1',
                Browser::BROWSER_CHROME,
                '141.0.7390.96',
            ],
            'Safari without Version token' => [
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Safari/605.1.15',
                Browser::BROWSER_SAFARI,
                Browser::VERSION_UNKNOWN,
            ],
        ];
    }
}
