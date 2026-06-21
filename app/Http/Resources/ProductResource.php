<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $images = [];
        // foreach ($this->images as $image) {
        //     $images[] = 'uploads/products' . $image->file_name;
        // }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'status' => $this->status ? 1 : 0,
            'views' => $this->views,
            'has_variants' => $this->has_variants ? 1 : 0,
            'has_discount' => $this->has_discount ? 1 : 0,
            'discount' => round($this->discount, 2),
            'quantity' => $this->quantity,
            'category_id' => $this->category->name,
            'brand_id' => $this->brand->name,
            'base_image' => $this->getImageUrl($this->images->first()->file_name, 'products'),
            'all_image' => $this->getImagesUrl($this->images->pluck('file_name'), 'products'),
            'product_price' => $this->when(
                $this->has_variants == false,
                function () {
                    return [
                        'price' => round($this->price, 2),
                        'price_after_discount' => $this->getPriceAfterDiscount(),
                    ];
                },
                function () {
                    $maxPriceVaraint = $this->productVariants()->orderByDesc('price')->first();
                    $minPriceVaraint = $this->productVariants()->orderBy('price')->first();
                    return [
                        'max_price' => $maxPriceVaraint->price,
                        'min_price' => $minPriceVaraint->price,
                    ];
                },
            ),

            'created_at'=>$this->formatDateLocatiazied($this->created_at , app()->locale,'l, d F Y , h:i a'),
        ];
    }
}
