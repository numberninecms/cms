<?php
/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NumberNine\Util\StringUtil;

use Symfony\Component\Finder\Finder;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;

final class ExtendedSlugger implements ExtendedSluggerInterface, LocaleAwareInterface
{
    private SluggerInterface $decoratedSlugger;
    private string $defaultLocale;

    public function __construct(SluggerInterface $slugger, string $defaultLocale)
    {
        $this->decoratedSlugger = $slugger;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @param string $directory
     * @param string $filename
     * @return string
     */
    public function getUniqueFilenameSlug(string $directory, string $filename): string
    {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = $this->decoratedSlugger->slug(pathinfo($filename, PATHINFO_FILENAME));

        $finder = new Finder();
        $finder->files()->name($basename . '*.' . $extension)->in($directory)->sortByName();

        $max = 0;

        foreach ($finder as $file) {
            if (!preg_match("@${basename}-(\d+)\.${extension}$@U", $file, $match)) {
                continue;
            }

            $max = max($max, (int)$match[1]);
        }

        // In case file doesn't exist
        if ($max === 0 && iterator_count($finder) === 0) {
            return sprintf('%s.%s', $basename, $extension);
        }

        return sprintf('%s-%02d.%s', $basename, $max + 1, $extension);
    }

    public function slug(string $string, string $separator = '-', string $locale = null): AbstractUnicodeString
    {
        return $this->decoratedSlugger->slug($string, $separator, $locale);
    }

    public function setLocale(string $locale): void
    {
        if (method_exists($this->decoratedSlugger, 'setLocale')) {
            $this->decoratedSlugger->setLocale($locale);
        }
    }

    public function getLocale()
    {
        return $this->defaultLocale;
    }
}
