<?php
declare(strict_types=1);

namespace App\Test\Service;


use App\Response\ParsePageResponseInterface;
use App\Service\DomainServiceInterface;
use App\Service\ImageServiceInterface;
use App\Service\ParsePageService;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Collection;
use PHPHtmlParser\Dom\HtmlNode;
use PHPHtmlParser\Dom\Tag;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ParsePageServiceTest extends TestCase
{
    private $parsePageService;
    private $expectedLinksArray = [
        'https://allo.ua/',
        'https://allo.ua/asdasd',
        'https://allo.ua/asdasdasd.html',
        '/asd2/1qwe',
    ];

    public function setUp(): void
    {
        $collectionMockLinks = $this->createMock(Collection::class);
        $collectionMockLinks->expects($this->once())
            ->method('toArray')
            ->willReturn($this->links());
        $collectionMockImages = $this->createMock(Collection::class);
        $collectionMockImages->expects($this->once())
            ->method('toArray')
            ->willReturn($this->images());;

        $domMock = $this->createMock(Dom::class);
        $domainServiceMock = $this->createMock(DomainServiceInterface::class);
        $domainServiceMock->expects($this->once())
            ->method('getDomainFromUrl')
            ->willReturn('https://allo.ua');
        $domainServiceMock->expects($this->once())
            ->method('getLinksToGo')
            ->willReturn($this->expectedLinksArray);
        $imageServiceMock = $this->createMock(ImageServiceInterface::class);
        $imageServiceMock->expects($this->once())
            ->method('getImagesFromDomain')
            ->willReturn([
                'https://allo.ua/qweqweqwe.jpg',
                '/asdasd/asdasd.png',
                '//allo.ua/asdasdq/asdasd/weqqwwe.jpg',
            ]);
        $domMock->expects($this->any())
            ->method($this->equalTo('find'))
            ->withConsecutive([$this->equalTo('a')], [$this->equalTo('img')])
            ->willReturnOnConsecutiveCalls($collectionMockLinks, $collectionMockImages);

        $this->parsePageService = new ParsePageService($domMock, $domainServiceMock, $imageServiceMock);
    }

    public function testExecute()
    {
        /**
         * @var ParsePageResponseInterface $result .
         */
        $result = $this->parsePageService->execute('https://allo.ua');
        $this->assertInstanceOf(ParsePageResponseInterface::class, $result);
        $this->assertEquals('https://allo.ua', $result->getParsedUrl());
        $this->assertEquals(3, $result->getImagesCount());
        $this->assertEquals($this->expectedLinksArray, $result->getLinksToVisit());
    }


    private function links()
    {
        $tagMock1 = $this->createMock(Tag::class);
        $tagMock2 = clone $tagMock1;
        $tagMock3 = clone $tagMock1;
        $tagMock4 = clone $tagMock1;
        $tagMock5 = clone $tagMock1;
        $tagMock6 = clone $tagMock1;
        $tagMock7 = clone $tagMock1;
        $htmlNodeMock1 = $this->createMock(HtmlNode::class);
        $htmlNodeMock2 = clone $htmlNodeMock1;
        $htmlNodeMock3 = clone $htmlNodeMock1;
        $htmlNodeMock4 = clone $htmlNodeMock1;
        $htmlNodeMock5 = clone $htmlNodeMock1;
        $htmlNodeMock6 = clone $htmlNodeMock1;
        $htmlNodeMock7 = clone $htmlNodeMock1;

        $tagMock1->expects($this->any())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/'
            ]);
        $htmlNodeMock1->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock1);

        $tagMock2->expects($this->any())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/asdasd'
            ]);
        $htmlNodeMock2->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock2);

        $tagMock3->expects($this->any())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/asdasd#asdas'
            ]);
        $htmlNodeMock3->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock3);

        $tagMock4->expects($this->any())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/asdasdasd.html'
            ]);
        $htmlNodeMock4->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock4);

        $tagMock5->expects($this->any())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/asd.jpg'
            ]);
        $htmlNodeMock5->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock5);

        $tagMock6->expects($this->any())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => 'https://allo.ua/asd2.pdf'
            ]);
        $htmlNodeMock6->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock6);

        $tagMock7->expects($this->any())
            ->method('getAttribute')
            ->with('href')
            ->willReturn([
                'value' => '/asd2/1qwe'
            ]);
        $htmlNodeMock7->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock7);

        return [
            $htmlNodeMock1,
            $htmlNodeMock2,
            $htmlNodeMock3,
            $htmlNodeMock4,
            $htmlNodeMock5,
            $htmlNodeMock6,
            $htmlNodeMock7,
        ];
    }

    private function images()
    {
        $tagMock1 = $this->createMock(Tag::class);
        $tagMock2 = clone $tagMock1;
        $tagMock3 = clone $tagMock1;
        $tagMock4 = clone $tagMock1;
        $htmlNodeMock1 = $this->createMock(HtmlNode::class);
        $htmlNodeMock2 = clone $htmlNodeMock1;
        $htmlNodeMock3 = clone $htmlNodeMock1;
        $htmlNodeMock4 = clone $htmlNodeMock1;

        $tagMock1->expects($this->any())
            ->method('getAttribute')
            ->with('src')
            ->willReturn([
                'value' => 'https://allo.ua/qweqweqwe.jpg'
            ]);
        $htmlNodeMock1->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock1);

        $tagMock2->expects($this->any())
            ->method('getAttribute')
            ->with('src')
            ->willReturn([
                'value' => '//allo.ua/asdasdq/asdasd/weqqwwe.jpg'
            ]);
        $htmlNodeMock2->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock2);

        $tagMock3->expects($this->any())
            ->method('getAttribute')
            ->with('src')
            ->willReturn([
                'value' => '/asdasd/asdasd.png'
            ]);
        $htmlNodeMock3->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock3);

        $tagMock4->expects($this->any())
            ->method('getAttribute')
            ->with('src')
            ->willReturn([
                'value' => 'https://i.allo.ua/asdasdasd.html'
            ]);
        $htmlNodeMock4->expects($this->any())
            ->method('getTag')
            ->willReturn($tagMock4);

        return [
            $htmlNodeMock1,
            $htmlNodeMock2,
            $htmlNodeMock3,
            $htmlNodeMock4,
        ];
    }
}