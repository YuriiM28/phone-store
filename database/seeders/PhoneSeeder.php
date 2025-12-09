<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Phone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class PhoneSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Faker\Provider\en_US\Person($faker));

        if (!Storage::exists('public/images/phones')) {
            Storage::makeDirectory('public/images/phones');
        }

        $brands = [
            ['name' => 'Apple', 'slug' => 'apple'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi'],
            ['name' => 'Huawei', 'slug' => 'huawei'],
            ['name' => 'Google', 'slug' => 'google'],
            ['name' => 'OnePlus', 'slug' => 'oneplus'],
            ['name' => 'Realme', 'slug' => 'realme'],
            ['name' => 'Oppo', 'slug' => 'oppo'],
            ['name' => 'Nothing', 'slug' => 'nothing'],
            ['name' => 'Asus', 'slug' => 'asus'],
            ['name' => 'Vivo', 'slug' => 'vivo'],
            ['name' => 'Motorola', 'slug' => 'motorola'],
            ['name' => 'Nokia', 'slug' => 'nokia'],
            ['name' => 'Honor', 'slug' => 'honor'],
            ['name' => 'Tecno', 'slug' => 'tecno'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $categories = [
            ['name' => 'Смартфоны', 'slug' => 'smartphones'],
            ['name' => 'Флагманы', 'slug' => 'flagships'],
            ['name' => 'Бюджетные', 'slug' => 'budget'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $phones = [
            [
                'name' => 'Samsung Galaxy S24',
                'slug' => 'samsung-galaxy-s24',
                'description' => 'Флагманский смартфон от Samsung с AI-функциями',
                'price' => 79990,
                'stock' => 15,
                'screen_size' => '6.2"',
                'ram' => '8 ГБ',
                'storage' => '256 ГБ',
                'camera' => '50 МП',
                'battery' => '4000 mAh',
                'processor' => 'Snapdragon 8 Gen 3',
                'brand_id' => 2,
                'category_id' => 2,
                'image' => 'https://c.dns-shop.ru/thumb/st4/fit/500/500/3f8c86981d4b5c94f499551923f62354/2c084e42f63f4d9dadcb32a1c197f1557e309a09d0b226478f1eb3f73510454a.jpg.webp',
            ],
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'Новый iPhone с титановым корпусом и мощным процессором A17 Pro. Поддержка USB-C и улучшенная камера.',
                'price' => 99990,
                'stock' => 10,
                'screen_size' => '6.1" Super Retina XDR',
                'ram' => '8 ГБ',
                'storage' => '128 ГБ',
                'camera' => '48 МП + 12 МП + 12 МП',
                'battery' => '3274 mAh',
                'processor' => 'Apple A17 Pro',
                'brand_id' => 1,
                'category_id' => 2,
                'image' => 'https://c.dns-shop.ru/thumb/st1/fit/500/500/31b28373068528817401be1fa0f72ff2/57c3fa75db8745654a371cbec253d141e7b1ac632f61e9dce6b5bd421941132e.jpg.webp',
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Флагманский смартфон от Samsung с S-Pen и AI-функциями. Титановый корпус и мощная камера.',
                'price' => 129990,
                'stock' => 8,
                'screen_size' => '6.8" Dynamic AMOLED 2X',
                'ram' => '12 ГБ',
                'storage' => '512 ГБ',
                'camera' => '200 МП + 50 МП + 12 МП + 10 МП',
                'battery' => '5000 mAh',
                'processor' => 'Snapdragon 8 Gen 3',
                'brand_id' => 2,
                'category_id' => 2,
                'image' => 'https://c.dns-shop.ru/thumb/st4/fit/0/0/36b5b5f1ce676a63224bd14807afec94/7bb18f6f859063ef21e86697f97589ab7bc609973e205108183e29b0bb173c8b.jpg.webp'
            ],
            [
                'name' => 'Xiaomi Redmi Note 13 Pro',
                'slug' => 'xiaomi-redmi-note-13-pro',
                'description' => 'Популярный смартфон с отличным соотношением цена/качество. Хорошая камера и быстрая зарядка.',
                'price' => 28900,
                'stock' => 25,
                'screen_size' => '6.67" AMOLED',
                'ram' => '8 ГБ',
                'storage' => '256 ГБ',
                'camera' => '200 МП + 8 МП + 2 МП',
                'battery' => '5100 mAh',
                'processor' => 'Snapdragon 7s Gen 2',
                'brand_id' => 3,
                'category_id' => 3,
                'image' => 'https://c.dns-shop.ru/thumb/st1/fit/0/0/ce0297f2b6675d6b55ad08aee91d6979/f3f9b6816cb8fef5506bda51783844c07a304b9316485667f179d1fb13b8dff9.jpg.webp'
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'slug' => 'google-pixel-8-pro',
                'description' => 'Смартфон от Google с чистейшим Android и лучшей камерой на рынке. AI-функции и долгая поддержка.',
                'price' => 89990,
                'stock' => 12,
                'screen_size' => '6.7" OLED',
                'ram' => '12 ГБ',
                'storage' => '128 ГБ',
                'camera' => '50 МП + 48 МП + 48 МП',
                'battery' => '5050 mAh',
                'processor' => 'Google Tensor G3',
                'brand_id' => 5,
                'category_id' => 2,
                'image' => 'https://c.dns-shop.ru/thumb/st4/fit/500/500/9b893b6ed1ce1fa2805df55c6d420fbd/7c732ab63ff0cacea590cb38ae774f00462eec74c469143abd24d34a510e4f9e.jpg.webp',
            ],
            [
                'name' => 'OnePlus 12',
                'slug' => 'oneplus-12',
                'description' => 'Мощный флагман с быстрой зарядкой и отличным экраном. Легендарная скорость OxygenOS.',
                'price' => 69990,
                'stock' => 15,
                'screen_size' => '6.82" LTPO AMOLED',
                'ram' => '12 ГБ',
                'storage' => '256 ГБ',
                'camera' => '50 МП + 64 МП + 48 МП',
                'battery' => '5400 mAh',
                'processor' => 'Snapdragon 8 Gen 3',
                'brand_id' => 6,
                'category_id' => 2,
                'image' => 'https://c.dns-shop.ru/thumb/st1/fit/500/500/0341419d986ab5e83a7d24c42aeea0d2/a6366186a4d3a528c169a0f05ac580a5b034926fe66b2df559416f23a8db61d9.jpg.webp',
            ],
            [
                'name' => 'Huawei P60 Pro',
                'slug' => 'huawei-p60-pro',
                'description' => 'Премиальный смартфон с уникальным дизайном и выдающейся камерой. Без сервисов Google.',
                'price' => 79990,
                'stock' => 5,
                'screen_size' => '6.67" OLED',
                'ram' => '8 ГБ',
                'storage' => '512 ГБ',
                'camera' => '48 МП + 13 МП + 48 МП',
                'battery' => '4815 mAh',
                'processor' => 'Snapdragon 8+ Gen 1',
                'brand_id' => 4,
                'category_id' => 2,
                'image' => 'https://c.dns-shop.ru/thumb/st1/fit/500/500/0d3f11a0871640d219368102d18e2390/793c58ee49e58d5c826baabffa9a27838ce6beae21b98c2c23d03f25fb27a651.jpg.webp',
            ],
            [
                'name' => 'Oppo Find X6 Pro',
                'slug' => 'oppo-find-x6-pro',
                'description' => 'Флагман с революционной камерой и премиальным дизайном. Быстрая зарядка и отличный экран.',
                'price' => 84990,
                'stock' => 7,
                'screen_size' => '6.82" AMOLED',
                'ram' => '12 ГБ',
                'storage' => '256 ГБ',
                'camera' => '50 МП + 50 МП + 50 МП',
                'battery' => '5000 mAh',
                'processor' => 'Snapdragon 8 Gen 2',
                'brand_id' => 8,
                'category_id' => 2,
                'image' => 'https://ir.ozone.ru/s3/multimedia-1-e/wc250/7988472806.jpg',
            ],
            [
                'name' => 'iPhone 14',
                'slug' => 'iphone-14',
                'description' => 'Популярный iPhone с процессором A15 Bionic и улучшенной системой безопасности.',
                'price' => 69990,
                'stock' => 18,
                'screen_size' => '6.1" Super Retina XDR',
                'ram' => '6 ГБ',
                'storage' => '128 ГБ',
                'camera' => '12 МП + 12 МП',
                'battery' => '3279 mAh',
                'processor' => 'Apple A15 Bionic',
                'brand_id' => 1,
                'category_id' => 1,
                'image' => 'https://c.dns-shop.ru/thumb/st4/fit/0/0/8c94c9a63c5b5231c3dc852ca07fe797/79b6b9cbb58bf72e530b8f421054342f84e2ad9e8066e381150639afb3b80917.jpg.webp',
            ],
            [
                'name' => 'Samsung Galaxy A54',
                'slug' => 'samsung-galaxy-a54',
                'description' => 'Средний класс с отличным AMOLED экраном и защитой от воды. Лучший в своем сегменте.',
                'price' => 34990,
                'stock' => 30,
                'screen_size' => '6.4" Super AMOLED',
                'ram' => '8 ГБ',
                'storage' => '128 ГБ',
                'camera' => '50 МП + 12 МП + 5 МП',
                'battery' => '5000 mAh',
                'processor' => 'Exynos 1380',
                'brand_id' => 2,
                'category_id' => 1,
                'image' => 'https://c.dns-shop.ru/thumb/st4/fit/0/0/198ec4dafa131b34ed4f79d3aa59f4b8/ba204b68392b41c1df1977e891fd0fa22479161221d6c2e53dda87c5009c7d4c.jpg.webp',
            ],
        ];

        foreach ($phones as $phoneData) {
            Phone::create($phoneData);
        }
    }
}
