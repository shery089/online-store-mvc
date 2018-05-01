<?php
/**
 * Mappings for all documents
 *
 * @package OpenLibs
 * 
 */
class User_mapping
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

    public function create_user_mapping() {
        $mapping = '{
            "settings": {
                "analysis": {
                    "analyzer": {
                        "urls-links-emails": {
                            "type": "custom",
                            "tokenizer": "uax_url_email"
                        }
                    }
                }
            },
            "mappings":{
                "user": {
                    "properties": {
                        "id" : {"type": "long"},
                        "first_name" : {"type": "text"},
                        "middle_name" : {"type": "text"},
                        "last_name" : {"type": "text"},
                        "image" : {"type": "text"},
                        "email" : {"type": "text", "analyzer": "urls-links-emails"},
                        "full_name" : {"type": "text", "index": "not_analyzed"},
                        "mobile_number" : {"type": "text"},
                        "profile_image" : {"type": "text"},
                        "thumbnail" : {"type": "text"},
                        "role" : {"type": "text"},
                        "role_id" : {"type": "byte"},
                        "joined_date" : {"type": "date", "format": "yyyy-MM-dd HH:mm:ss"},
                        "updated_date" : {"type": "date", "format": "yyyy-MM-dd HH:mm:ss"}
                    }
                }
            }
        }';

        return $mapping;

    }
}