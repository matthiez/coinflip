<?php namespace App\Traits;

trait InventoryTrait
{
    public $bots = [
        [
            'name' => 'DummyBot',
            'steamid' => 4214124512515125151
        ],
        [
            'name' => 'DummyBot2',
            'steamid' => 4214124512515125152
        ]
    ];

    public static function getMarketHashNames($inventory, $assetIds) {
        $rgInventory = self::loopInventory($inventory['rgInventory']);
        $rgDescriptions = self::loopDescriptions($inventory['rgDescriptions']);
        foreach ($rgInventory as $item) {
            foreach ($assetIds as $assetId) {
                if (isset($item['assetID'])) {
                    if ($assetId == $item['assetID']) {
                        $marketHashNames[] = $rgDescriptions[$item['classID'] . '_' . $item['instanceID']]['market_hash_name'];
                    }
                }
            }
        }
        return (!empty($marketHashNames)) ? $marketHashNames : [];
    }

    public static function getItemRarity($rarity) {
        if (stripos($rarity, 'Base Grade') !== false) return "base";
        if (stripos($rarity, 'Consumer Grade') !== false) return "consumer";
        if (stripos($rarity, 'Industrial Grade') !== false) return "industrial";
        if (stripos($rarity, 'Mil-spec') !== false) return "mil-spec";
        if (stripos($rarity, 'Restricted') !== false) return "restricted";
        if (stripos($rarity, 'Classified') !== false) return "classified";
        if (stripos($rarity, 'Covert') !== false) return "covert";
        if (stripos($rarity, 'Exceedingly Rare') !== false) return "rare";
        if (stripos($rarity, 'Contraband') !== false) return "contraband";
        return '';
    }

    private static function loopInventory($rgInventory) {
        if (empty($rgInventory)) return ['error' => 'This looks like an empty inventory.'];
        foreach ($rgInventory as $key => $value) {
            $rgInventory[$key]['assetID'] = $value['id'];
            $rgInventory[$key]['classID'] = $value['classid'];
            $rgInventory[$key]['instanceID'] = $value['instanceid'];
        }
        return (!empty($rgInventory)) ? $rgInventory : [];
    }

    private static function loopDescriptions($rgDescriptions) {
        if (empty($rgDescriptions)) return ['error' => 'This looks like an empty inventory.'];
        foreach ($rgDescriptions as $key => $value) {
            $rgDescriptions[$key]['market_hash_name'] = $value['market_hash_name'];
            $rgDescriptions[$key]['icon_url'] = $value['icon_url'];
            $rgDescriptions[$key]['type'] = $value['type'];
        }
        return (!empty($rgDescriptions)) ? $rgDescriptions : [];
    }
}
