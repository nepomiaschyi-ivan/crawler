<?php
declare(strict_types=1);

namespace App\Test\Service;


use App\Service\DomainService;
use PHPHtmlParser\Dom\HtmlNode;
use PHPHtmlParser\Dom\Tag;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class DomainServiceTest extends TestCase
{
    private $domainService;

    public function setUp(): void
    {
        $this->domainService = new DomainService();
    }

    /**
     * @param $url
     * @param $expectedDomain
     * @dataProvider getUrls
     */
    public function testGetDomainFromUrl($url, $expectedDomain)
    {
        $result = $this->domainService->getDomainFromUrl($url);

        $this->assertEquals($result, $expectedDomain);
    }

    public function testGetLinksToGo()
    {
        $links = $this->links();
        $expected = [
            'https://allo.ua/aaa',
            'https://allo.ua/asdasd.html',
            'https://allo.ua/aaa/bbb'
        ];
        $result = $this->domainService->getLinksToGo($links, 'https://allo.ua');
        $this->assertEquals($expected, $result);
    }

    public function getUrls()
    {
        return [
            ['https://allo.ua/aaa', 'https://allo.ua'],
            ['allo.ua/aaa', null],
            ['https://allo.ua/aaa#qqweqw', 'https://allo.ua']
        ];
    }

    private function links()
    {
        $tagMock1 = $this->createMock(Tag::class);
        $tagMock2 = clone $tagMock1;
        $tagMock3 = clone $tagMock1;
        $tagMock4 = clone $tagMock1;
        $tagMock5 = clone $tagMock1;
        $htmlNodeMock1 = $this->createMock(HtmlNode::class);
        $htmlNodeMock2 = clone $htmlNodeMock1;
        $htmlNodeMock3 = clone $htmlNodeMock1;
        $htmlNodeMock4 = clone $htmlNodeMock1;
        $htmlNodeMock5 = clone $htmlNodeMock1;

        $tagMock1->expects($this->once())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/aaa'
            ]);
        $htmlNodeMock1->expects($this->once())
            ->method('getTag')
            ->willReturn($tagMock1);

        $tagMock2->expects($this->once())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/aaa#qweqw'
            ]);
        $htmlNodeMock2->expects($this->once())
            ->method('getTag')
            ->willReturn($tagMock2);

        $tagMock3->expects($this->once())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/asdasd.html'
            ]);
        $htmlNodeMock3->expects($this->once())
            ->method('getTag')
            ->willReturn($tagMock3);

        $tagMock4->expects($this->once())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/aaa.pdf'
            ]);
        $htmlNodeMock4->expects($this->once())
            ->method('getTag')
            ->willReturn($tagMock4);

        $tagMock5->expects($this->once())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => '/aaa/bbb'
            ]);
        $htmlNodeMock5->expects($this->once())
            ->method('getTag')
            ->willReturn($tagMock5);

        return [
            $htmlNodeMock1,
            $htmlNodeMock2,
            $htmlNodeMock3,
            $htmlNodeMock4,
            $htmlNodeMock5,
        ];
    }
}