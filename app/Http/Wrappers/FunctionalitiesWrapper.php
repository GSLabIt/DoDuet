<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\Functionalities;
use App\Models\Platforms;
use App\Models\User;
use App\Models\UserSegments;
use Illuminate\Http\Request;

class FunctionalitiesWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance, depending on the argument type this will call initWithUser or initWithRequest
     *
     * @param User|Request $initializer
     * @return FunctionalitiesWrapper|null
     */
    public static function init($initializer): ?FunctionalitiesWrapper
    {
        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if($initializer instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            return (new static)->initWithUser($initializer);
        }
        elseif($initializer instanceof Request) {
            // init a new instance of the class and finally call the method with the request instance
            return (new static)->initWithRequest($initializer);
        }
        return null;
    }

    /**
     * Initialize the wrapper with a user instance
     *
     * @param User $user
     * @return $this
     */
    private function initWithUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Initialize the wrapper with a request instance
     *
     * @param Request $request
     * @return $this
     */
    private function initWithRequest(Request $request): static
    {
        $this->user = $request->user();
        return $this;
    }

    /**
     * Get a component by its name and platform
     *
     * @param string $component_name
     * @param string $platform_name
     * @return Functionalities|null
     */
    public function getComponent(string $component_name, string $platform_name): ?Functionalities {
        // retrieve all the functionalities with the defined name
        $functionalities = Functionalities::where("name", $component_name)->get();

        // initialize the functionality instance to null
        $component = null;

        // loop through all the functionalities one by one
        foreach ($functionalities as $functionality) {
            // for each functionality check that the associated platform is the requested platform, if it is then
            // the functionality we're looking for is found
            if(!is_null($functionality->platforms()->where("name", $platform_name)->first())) {
                // assign the matching functionality to the component instance
                $component = $functionality;
                break;
            }
        }

        return $component;
    }

    private function handleComponentsDataExtraction(string|Functionalities $component, string|Platforms $platform): array {
        $component_name = $component instanceof Functionalities ? $component->name : $component;
        $platform_name = $platform instanceof Platforms ? $platform->name : $platform;
        return [$component_name, $platform_name];
    }

    /**
     * Check if a component exists for a platform
     *
     * @param string|Functionalities $component
     * @param string|Platforms $platform
     * @return bool
     */
    public function hasComponent(string|Functionalities $component, string|Platforms $platform): bool {
        [$component_name, $platform_name] = $this->handleComponentsDataExtraction($component, $platform);
        return !is_null($this->getComponent($component_name, $platform_name));
    }

    /**
     * Checks if a component exists for a platform and if it is a controller component
     *
     * @param string|Functionalities $component
     * @param string|Platforms $platform
     * @return bool
     */
    public function isController(string|Functionalities $component, string|Platforms $platform): bool {
        [$component_name, $platform_name] = $this->handleComponentsDataExtraction($component, $platform);
        $component = $this->getComponent($component_name, $platform_name);
        return !is_null($component) && $component->is_controller;
    }

    /**
     * Checks if a component exists for a platform and if it is a controller component under test
     *
     * @param string|Functionalities $component
     * @param string|Platforms $platform
     * @return bool
     */
    public function isTestingController(string|Functionalities $component, string|Platforms $platform): bool {
        [$component_name, $platform_name] = $this->handleComponentsDataExtraction($component, $platform);
        $component = $this->getComponent($component_name, $platform_name);
        return !is_null($component) && $component->is_controller && $component->is_testing;
    }

    /**
     * Check if a component exists for a platform and if it is an ui component
     *
     * @param string|Functionalities $component
     * @param string|Platforms $platform
     * @return bool
     */
    public function isUserInterface(string|Functionalities $component, string|Platforms $platform): bool {
        [$component_name, $platform_name] = $this->handleComponentsDataExtraction($component, $platform);
        $component = $this->getComponent($component_name, $platform_name);
        return !is_null($component) && $component->is_ui;
    }

    /**
     * Check if a component exists for a platform and if it is an ui component under test
     *
     * @param string|Functionalities $component
     * @param string|Platforms $platform
     * @return bool
     */
    public function isTestingUserInterface(string|Functionalities $component, string|Platforms $platform): bool {
        [$component_name, $platform_name] = $this->handleComponentsDataExtraction($component, $platform);
        $component = $this->getComponent($component_name, $platform_name);
        return !is_null($component) && $component->is_ui && $component->is_testing;
    }

    /**
     * Retrieve a functionality given its component name and platform from a user segment if it has one.
     * Returns null if no functionality is found
     *
     * @param string|Functionalities $component
     * @param string|Platforms $platform
     * @param string|UserSegments $segment
     * @return Functionalities|null
     */
    public function getSegmentFunctionality(string|Functionalities $component, string|Platforms $platform, string|UserSegments $segment): ?Functionalities {
        [$component_name, $platform_name] = $this->handleComponentsDataExtraction($component, $platform);
        $component = $this->getComponent($component_name, $platform_name);

        $segment = $segment instanceof UserSegments ? $segment : UserSegments::whereId($segment)->first();

        // check if component and segment exists then checks if the functionality is linked with the given segment
        // if it is, return the functionality otherwise null is returned
        if(!is_null($component) && !is_null($segment)) {
            return $segment->functionalities()->where("functionalities_id", $component->id)->first();
        }

        return null;
    }

    /**
     * Check if a user segment has a functionality identified by a component and platform name
     *
     * @param string|Functionalities $component
     * @param string|Platforms $platform
     * @param string|UserSegments $segment
     * @return bool
     */
    public function segmentHasFunctionality(string|Functionalities $component, string|Platforms $platform, string|UserSegments $segment): bool {
        // retrieve the segment functionality if it exists, returns null if it doesn't. We check for nullity only because
        // all other checks are already performed by the getSegmentFunctionality method
        return !is_null($this->getSegmentFunctionality($component, $platform, $segment));
    }

    /**
     * Check if a component is active for a user segment or for the current user.
     * Note: components not under test are enabled for all the users everytime
     *
     * @param string|Functionalities $component
     * @param string|Platforms $platform
     * @param UserSegments|null $segment
     * @return bool
     */
    public function isComponentActive(string|Functionalities $component, string|Platforms $platform, UserSegments $segment = null): bool {
        [$component_name, $platform_name] = $this->handleComponentsDataExtraction($component, $platform);
        $component = $this->getComponent($component_name, $platform_name);

        // check if component exists
        if(!is_null($component)) {
            // Components that have passed the testing phase are considered enabled for all the users
            if(!$component->is_testing) {
                return true;
            }

            // check if a custom user segment is provided
            if(is_null($segment)) {
                // if no custom user segment is provided the ones from the user's instance will be used (multiple loops needed)
                $segments = $this->user->userSegments;
                foreach ($segments as $segment) {
                    // for each segment retrieve the eventual functionality
                    $segment_functionality = $this->getSegmentFunctionality($component, $platform, $segment);

                    // check if the functionality exists and is active in case, return
                    // NOTE: if the user is under multiple segments a segment having a functionality enabled is
                    //  enough to enable the functionality for the user.
                    //  The checks keeps going in order to grant this behaviour
                    if(!is_null($segment_functionality) && (bool) $segment_functionality->pivot->is_active) {
                        return true;
                    }
                }
            }
            else {
                // retrieve the segment functionality
                $segment_functionality = $this->getSegmentFunctionality($component, $platform, $segment);

                // check if the functionality exists and is active
                return !is_null($segment_functionality) && (bool) $segment_functionality->pivot->is_active;
            }
        }
        return false;
    }
}
