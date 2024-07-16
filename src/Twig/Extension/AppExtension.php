<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AppExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('truncate', [$this, 'tronque']),
            new TwigFilter('pluralize', [$this, 'pluralize']),

            new TwigFilter('unique', [$this, 'uniqueFilter']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fnTrunc', [$this, 'InnerFnTrunc']),
            new TwigFunction('trunc', [$this, 'trunc']),
        ];
    }
    public function InnerTruncate($value, $length = 40, $end = '...'): string {
        return strlen($value) <= $length ? $value : substr($value, 0, $length) . $end;
    }
        public function InnerFnTrunc($value, $length = 15, $end = '...'): string {
        return strlen($value) <= $length ? $value : substr($value, 0, $length) . $end;
    }
    public function tronque($valeur){
        if (strlen($valeur) >= 40){
            return substr($valeur, 0, 40).' ...';
        }else{
            return $valeur;
        }
    }
    public function trunc($valeur){
        if (strlen($valeur) >= 15){
            return substr($valeur, 0, 15).' ...';
        }else{
            return $valeur;
        }
    }
    public function uniqueFilter(array $array): array {
        return array_unique($array);
    }
    public function pluralize(int $count, string $singular, ?string $plural = null): string {
        $plural = $plural ?? $singular . 's';
        return $count === 1 ? "$count $singular" : "$count $plural";
    }
}
