<?php

namespace TGHP\RealpageClient\Service;

class PricingAndAvailability extends AbstractService
{

    protected function getElementMap()
    {
        return [
            // getPickList
            'GetPickList' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },
            'Contents' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\repeatingElements($reader, 'PicklistItem');
            },
            'PicklistItem' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },

            // getFloorPlanList
            'GetFloorPlanList' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\repeatingElements($reader, 'FloorPlanObject');
            },
            'FloorPlanObject' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },

            // getUnitList
            'GetUnitList' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\repeatingElements($reader, 'UnitObjects');
            },
            'UnitObjects' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\repeatingElements($reader, 'UnitObject');
            },
            'UnitObject' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },
            'Availability' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },
            'Accessibility' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },
            'Address' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },
            'UnitDetails' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },
            'FloorPlan' => function (\Sabre\Xml\Reader $reader) {
                return \Sabre\Xml\Deserializer\keyValue($reader, '');
            },

        ];
    }

    /**
     * Picklist
     */

    const LIST_TYPE_BUILDING = 'LIST_BUILDING';
    const LIST_TYPE_CONTACT = 'LIST_CONTACT';
    const LIST_TYPE_FLOORPLANGROUP = 'LIST_FLOORPLANGROUP';
    const LIST_TYPE_JOB = 'LIST_JOB';
    const LIST_TYPE_LATEMETHOD = 'LIST_LATEMETHOD';
    const LIST_TYPE_LEADSOURCE = 'LIST_LEADSOURCE';
    const LIST_TYPE_LEASEREASON = 'LIST_LEASEREASON';
    const LIST_TYPE_LEASETERM = 'LIST_LEASETERM';
    const LIST_TYPE_OPTIONS = 'LIST_OPTIONS';
    const LIST_TYPE_PET = 'LIST_PET';
    const LIST_TYPE_PETWEIGHT = 'LIST_PETWEIGHT';
    const LIST_TYPE_PRICERANGE = 'LIST_PRICERANGE';
    const LIST_TYPE_PHONETYPE = 'LIST_PHONETYPE';
    const LIST_TYPE_RELATION = 'LIST_RELATION';
    const LIST_TYPE_TRAFFICSOURCE = 'LIST_TRAFFICSOURCE';
    const LIST_TYPE_UNIT = 'LIST_UNIT';
    const LIST_TYPE_VEHICLE = 'LIST_VEHICLE';
    const LIST_TYPE_LEASINGAGENT = 'LIST_LEASINGAGENT';
    const LIST_TYPE_REASONMOVING = 'LIST_REASONMOVING';
    const LIST_TYPE_ACTIVITYLOSSREASON = 'LIST_ACTIVITYLOSSREASON';
    const LIST_TYPE_ACTIVITYRESULTCODE = 'LIST_ACTIVITYRESULTCODE';
    const LIST_TYPE_ACTIVITYTYPE = 'LIST_ACTIVITYTYPE';
    const LIST_TYPE_TASKPRIORITY = 'LIST_TASKPRIORITY';
    const LIST_TYPE_TASKCATEGORY = 'LIST_TASKCATEGORY';
    const LIST_TYPE_PROSPECTSTATUS = 'LIST_PROSPECTSTATUS';

    public function getPickList($type)
    {
        return $this->call('GetPickList', 'getpicklistResult', [
            'lType' => $type
        ]);
    }

    /**
     * Floor Plan
     */
    const FLOORPLANLIST_CRITERION_FLOOR_PLAN_CODE = 'FloorPlanCode';
    const FLOORPLANLIST_CRITERION_FLOOR_PLAN_ID = 'FloorPlanID';
    const FLOORPLANLIST_CRITERION_FLOOR_PLAN_NAME = 'FloorPlanName';

    public function getFloorPlanList($criterionName = false, $criterionValue = false)
    {
        $args = [];

        if($criterionName && $criterionValue) {
            $args['listCriteria'] = [
                'ListCriterion' => [
                    'name' => $criterionName,
                    'singlevalue' => $criterionValue
                ]
            ];
        }

        return $this->call('GetFloorPlanList', 'getfloorplanlistResult', $args);
    }

    /**
     * Units
     */
    const UNITLIST_CRITERION_BUILDING_ID = 'BuildingID';
    const UNITLIST_CRITERION_INCLUDE_ALL_LEASE_TERMS = 'IncludeAllLeaseTerms';
    const UNITLIST_CRITERION_BUILDING_NUMBER = 'BuildingNumber';
    const UNITLIST_CRITERION_DATE_NEEDED = 'DateNeeded';
    const UNITLIST_CRITERION_FLOOR_LEVEL = 'FloorLevel';
    const UNITLIST_CRITERION_FLOOR_PLAN_GROUP_ID = 'FloorPlanGroupID';
    const UNITLIST_CRITERION_FLOOR_PLAN_CODE = 'FloorPlanCode';
    const UNITLIST_CRITERION_FLOOR_PLAN_ID = 'FloorPlanID';
    const UNITLIST_CRITERION_INCLUDE_RENT_MATRIX = 'IncludeRentMatrix';
    const UNITLIST_CRITERION_LEASE_TERM = 'LeaseTerm';
    const UNITLIST_CRITERION_LEASE_TERM_ID = 'LeaseTermID';
    const UNITLIST_CRITERION_LIMIT_RESULTS = 'LimitResults';
    const UNITLIST_CRITERION_NUMBER_BATHROOMS = 'NumberBathrooms';
    const UNITLIST_CRITERION_NUMBER_BEDROOMS = 'NumberBedrooms';
    const UNITLIST_CRITERION_PRICE_RANGE_ID = 'PriceRangeID';
    const UNITLIST_CRITERION_UNIT_ID = 'UnitID';
    const UNITLIST_CRITERION_UNIT_NUMBER = 'UnitNumber';
    const UNITLIST_CRITERION_USE_STANDARD_BUSINESS_FLOW = 'UseStandardBusinessFlow';

    public function getUnitList($criterion = [])
    {
        $args = [];

        if(!empty($criterion)) {
            $args['listCriteria'] = [];
            $args['listCriteria']['ListCriterion'] = [];

            foreach($criterion as $key => $value) {
                $criterion = [
                    'name' => $key,
                ];

                if(!is_array($value) || count($value) === 1) {
                    $criterion['singlevalue'] = $value;
                } else if (count($value) === 2) {
                    $criterion['minvalue'] = $value[0];
                    $criterion['maxvalue'] = $value[1];
                }

                $args['listCriteria']['ListCriterion'][] = $criterion;
            }
        }

        return $this->call('GetUnitList', 'getunitlistResult', $args);
    }

}