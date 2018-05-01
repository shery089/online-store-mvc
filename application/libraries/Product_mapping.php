<?php
/**
 * Mappings for all documents
 *
 * @package OpenLibs
 * 
 */
class Product_mapping
{
    public $index;

    /**
     * Handling the call for every function with curl
     * 
     * @param type $path
     * @param type $method
     * @param type $data
     * 
     * @return type
     * @throws Exception
     */

    public function create_product_mapping($to_prepend_mapping_keys) {
        $prepend = '';
        foreach($to_prepend_mapping_keys as $to_prepend_mapping_key){
            $prepend .= "\n\r" . '"' . $to_prepend_mapping_key .  '" : {"type": "text", "index": "not_analyzed"},';
        }

        $mapping = '{
            "settings": {
                "analysis": {
                    "analyzer": {
                        "hyphen": {
                            "type": "custom",
                            "tokenizer": "hyphen",
                            "filter" : [ "hyphen" ]
                        }
                    }
                }
            },
            "mappings":{
                "product": {
                    "properties": {
                        "id" : {"type": "long"},
                        "name" : {"type": "text", "index": "not_analyzed", "analyzer": "hyphen"},
                        "category_id" : {"type": "short"},
                        "category_name" : {"type": "text", "index": "not_analyzed", "analyzer": "hyphen"},
                        "product_attribute_detail_id" : {"type": "text"},
                        "product_attribute_detail_value" : {"type": "text"},
                        "image" : {"type": "text"},
                        "profile_image" : {"type": "text"},
                        "thumbnail" : {"type": "text"},
                        "short_description" : {"type": "text"},
                        "long_description" : {"type": "text"},
                        "role" : {"type": "text"},
                        "role_id" : {"type": "byte"},'

                        . $prepend .

                        '"joined_date" : {"type": "date", "format": "yyyy-MM-dd HH:mm:ss"},
                        "updated_date" : {"type": "date", "format": "yyyy-MM-dd HH:mm:ss"}
                    }
                }
            }
        }';

        return $mapping;

    }
}