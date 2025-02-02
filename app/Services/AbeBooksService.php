<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AbeBooksService
{
    private $pricingUrl = "https://www.abebooks.com/servlet/DWRestService/pricingservice";
    private $recommendationsUrl = "https://www.abebooks.com/servlet/RecommendationsApi";

    private function getPrice($payload)
    {
        $response = Http::asForm()->post($this->pricingUrl, $payload);
        return $response->json();
    }

    private function getRecommendations($payload)
    {
        $response = Http::get($this->recommendationsUrl, $payload);
        return $response->json();
    }

    public function getPriceByISBN($isbn)
    {
        $payload = [
            'action' => 'getPricingDataByISBN',
            'isbn' => $isbn,
            'container' => "pricingService-$isbn"
        ];
        return $this->getPrice($payload);
    }

    public function getPriceByAuthorTitle($author, $title)
    {
        $payload = [
            'action' => 'getPricingDataForAuthorTitleStandardAddToBasket',
            'an' => $author,
            'tn' => $title,
            'container' => "oe-search-all"
        ];
        return $this->getPrice($payload);
    }

    public function getRecommendationsByISBN($isbn)
    {
        $payload = [
            'pageId' => 'plp',
            'itemIsbn13' => $isbn
        ];
        return $this->getRecommendations($payload);
    }
}
