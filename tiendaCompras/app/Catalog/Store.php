<?php

namespace App\Catalog;

use JsonSerializable;

/**
 * Una sucursal física de la tienda.
 */
final readonly class Store implements JsonSerializable
{
    /**
     * @param  string  $name  Nombre de la sucursal.
     * @param  string  $address  Dirección física.
     * @param  list<string>  $zones  Zonas donde la sucursal hace delivery.
     * @param  string  $whatsapp  Número de WhatsApp con código de país.
     * @param  string  $mapUrl  Enlace a la ubicación en el mapa.
     */
    public function __construct(
        public string $name,
        public string $address,
        public array $zones,
        public string $whatsapp,
        public string $mapUrl,
    ) {}

    /**
     * Get the store as a JSON serializable array.
     *
     * @return array{name: string, address: string, zones: list<string>, whatsapp: string, mapUrl: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'zones' => $this->zones,
            'whatsapp' => $this->whatsapp,
            'mapUrl' => $this->mapUrl,
        ];
    }
}
