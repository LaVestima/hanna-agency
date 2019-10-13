<?php

namespace App\Repository\Elastica;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use FOS\ElasticaBundle\Repository;

class ProductRepository extends Repository
{
    public function search($search = null, $limit = 10)
    {
        $query = new Query();

        $boolQuery = new BoolQuery();

        if ($search !== null) {
            $fieldQuery = new Query\MatchPhrasePrefix();
            $fieldQuery->setField('name', $search);

            $boolQuery->addMust($fieldQuery);
        }

        $query->setQuery($boolQuery);
        $query->setSize($limit);

        return $this->find($query);
    }
}
