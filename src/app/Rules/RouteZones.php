<?php

namespace App\Rules;

use App\Airport;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Validation\Validator;

class RouteZones implements Rule
{
    /**
     * @var array $data
     */
    private $data;

    /**
     * @var float $eps
     */
    private $eps;

    /**
     * Create a new rule instance.
     *
     * @param $parameters
     * @param Validator $validator
     */
    public function __construct($parameters, Validator $validator)
    {
        $this->data = $validator->getData();
        $this->eps = 0.001;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $route
     *
     * @return bool
     */
    public function passes($attribute, $route)
    {
        if (!isset($this->data['airport_from_id']) || !isset($this->data['airport_to_id'])) {
            return false;
        }

        $airportFrom = Airport::find($this->data['airport_from_id']);
        $airportTo   = Airport::find($this->data['airport_to_id']);

        $route_points = json_decode($route);
        $airportFromLatlon = [$airportFrom->lat, $airportFrom->lon];
        $airportToLatlon = [$airportTo->lat, $airportTo->lon];

        // init route polyline object
        $points = array_merge([$airportFromLatlon], $route_points, [$airportToLatlon]);

        $zones = array_merge(config('zones.prohibited'), config('zones.dangerous'));

        // check whether any of the route segments intersects any zone
        foreach ($zones as $zone) {
            if ($zone['type'] == 'circle') {

                $C = $zone['center'];

                // check whether segments intersect circle
                for ($i = 0; $i < count($points) - 1; $i++) {
                    $A = $points[$i];
                    $B = $points[$i + 1];

                    $CA = haversineDistance($A[0], $A[1], $C[0], $C[1]);
                    $CB = haversineDistance($B[0], $B[1], $C[0], $C[1]);

                    // check if segment points are in circle
                    if($CA <= $zone['radius'] or $CB <= $zone['radius']) {
                        return false;
                    }

                    // intersection point (point on segment closest to the circle center)
                    $X = [0, 0];

                    if (abs($A[0] - $B[0]) < $this->eps and abs($A[1] - $B[1]) < $this->eps) {
                        // if the segment length is really small, just approximate it with one of its points
                        $X = $B;
                    }
                    else {
                        // normal line coefs
                        $a = $A[0] - $B[0];
                        $b = $A[1] - $B[1];
                        $c = -($a * $C[0]) - ($b * $C[1]);

                        // segment line coefs
                        $p = $b;
                        $q = -$a;
                        $r = -($p * $A[0]) - ($q * $A[1]);

                        if ($b) {
                            $X[0] = ($q * $c - $b * $r) / ($b * $p - $a * $q);
                            $X[1] = -($a * $X[0] + $c) / $b;
                        }
                        else {
                            $X[1] = ($b * $r - $q * $c) / ($q * $a - $p * $b);
                            $X[0] = -($p * $X[1] + $r) / $q;
                        }
                    }

                    $CX = haversineDistance($X[0], $X[1], $C[0], $C[1]);

                    if ($CX < $zone['radius']) {
                        // if the closest point on the segment line is inside the circle, check if that point is on the segment

                        if($this->isPointOnSegment($X, $A, $B)) {
                            return false;
                        }
                    }
                }
            }

            if ($zone['type'] == 'poly') {
                // check whether segments intersect polygon segments

                for ($i = 0; $i < count($zone['shape']) - 1; $i++){
                    $C = $zone['shape'][$i];
                    $D = $zone['shape'][$i + 1];

                    for ($j = 0; $j < count($points) - 1; $j++) {
                        $A = $points[$j];
                        $B = $points[$j + 1];

                        // intersection point of segments lines
                        $X = [0, 0];

                        if (abs($A[0] - $B[0]) < $this->eps and abs($A[1] - $B[1]) < $this->eps) {
                            // if the segment length is really small, just if one of the point is on the line it with one of its points

                            if($this->isPointOnSegment($A, $C, $D)) {
                                return false;
                            }
                        }
                        else {
                            // route segment vec
                            $u = $A[0] - $B[0];
                            $v = $A[1] - $B[1];

                            // route segment coefs
                            $a = $v;
                            $b = -$u;
                            $c = -($a * $A[0]) - ($b * $A[1]);

                            // zone segment vec
                            $u = $C[0] - $D[0];
                            $v = $C[1] - $D[1];

                            // zone segment coefs
                            $p = $v;
                            $q = -$u;
                            $r = -($a * $C[0]) - ($b * $C[1]);

                            // check segments collinearity
                            if ((abs($a / $b) - abs($p / $q) < $this->eps)) {
                                // look for shared points
                                if ($this->isPointOnSegment($A, $C, $D) or $this->isPointOnSegment($B, $C, $D)) {
                                    return false;
                                }
                            }

                            if ($b) {
                                $X[0] = ($q * $c - $b * $r) / ($b * $p - $a * $q);
                                $X[1] = -($a * $X[0] + $c) / $b;
                            }
                            else {
                                $X[1] = ($b * $r - $q * $c) / ($q * $a - $p * $b);
                                $X[0] = -($p * $X[1] + $r) / $q;
                            }
                        }

                        // check if segments intersection lies on both segments
                        if ($this->isPointOnSegment($X, $A, $B) and $this->isPointOnSegment($X, $C, $D)) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Check if point $X lies on the segment $A-$B
     * @param $X
     * @param $A
     * @param $B
     * @return bool
     */
    private function isPointOnSegment($X, $A, $B) {
        $ABx = $A[0] - $B[0];
        $ABy = $A[1] - $B[1];
        $AB = $ABx * $ABx + $ABy * $ABy;

        $AXx = $A[0] - $X[0];
        $AXy = $A[1] - $X[1];
        $AX = $AXx * $AXx + $AXy * $AXy;

        $BXx = $B[0] - $X[0];
        $BXy = $B[1] - $X[1];
        $BX = $BXx * $BXx + $BXy * $BXy;

        return $AX + $BX - $AB <= $this->eps;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
