<?php

namespace Tests\Unit;

use App\Services\I18n\LinkRewriter;
use PHPUnit\Framework\TestCase;

class LinkRewriterTest extends TestCase
{
    private function rw(mixed $v): mixed
    {
        return LinkRewriter::rewrite($v, 'ua', 'ka');
    }

    public function test_relative_paths(): void
    {
        $this->assertSame('/ka', $this->rw('/ua'));
        $this->assertSame('/ka/catalog', $this->rw('/ua/catalog'));
        $this->assertSame('/ka?x=1', $this->rw('/ua?x=1'));
        $this->assertSame('/uah', $this->rw('/uah'));
        $this->assertSame('/ru/catalog', $this->rw('/ru/catalog'));
        $this->assertSame('https://example.com/ua/x', $this->rw('https://example.com/ua/x'));
    }

    public function test_html_attributes(): void
    {
        $this->assertSame(
            '<a href="/ka/contacts">Link</a> <img src=\'/ka/img.png\'> <a href="/ka">h</a>',
            $this->rw('<a href="/ua/contacts">Link</a> <img src=\'/ua/img.png\'> <a href="/ua">h</a>')
        );
    }

    public function test_text_is_not_touched(): void
    {
        $text = '<p>Go to /ua/catalog or see ua/ and /ua. data-x="/ua/y"</p>';
        $this->assertSame($text, $this->rw($text));
    }

    public function test_absolute_urls(): void
    {
        $this->assertSame('https://gutgas.eu/ka/page', $this->rw('https://gutgas.eu/ua/page'));
        $this->assertSame('https://www.gutgas.eu/ka', $this->rw('https://www.gutgas.eu/ua'));
        $this->assertSame('<a href="https://gutgas.eu/ka/p">x</a>', $this->rw('<a href="https://gutgas.eu/ua/p">x</a>'));
        $this->assertSame('https://gutgas.eu/uah', $this->rw('https://gutgas.eu/uah'));
        $this->assertSame('https://gutgas.eu/ru/p', $this->rw('https://gutgas.eu/ru/p'));
    }

    public function test_nested_arrays_keep_keys_and_non_strings(): void
    {
        $this->assertSame(
            [['title' => 'T', 'url' => '/ka/a', 'n' => 3, 'html' => '<a href="/ka/b">b</a>']],
            $this->rw([['title' => 'T', 'url' => '/ua/a', 'n' => 3, 'html' => '<a href="/ua/b">b</a>']])
        );
    }

    public function test_other_target_locale(): void
    {
        $this->assertSame('/en/catalog', LinkRewriter::rewrite('/ua/catalog', 'ua', 'en'));
        $this->assertSame(
            '<a href="/ro/x">a</a> https://gutgas.eu/ro',
            LinkRewriter::rewrite('<a href="/ua/x">a</a> https://gutgas.eu/ua', 'ua', 'ro')
        );
    }
}
