<?php
namespace Nicolasey\Personnages\Http\Controllers;

use Illuminate\Routing\Controller;
use Nicolasey\Personnages\Events\PersonnageActivated;
use Nicolasey\Personnages\Events\PersonnageDeactivated;
use Nicolasey\Personnages\Events\PersonnageKilled;
use Nicolasey\Personnages\Events\PersonnageResurrected;
use Nicolasey\Personnages\Models\Personnage;

class PersonnageController extends Controller
{
    /**
     * Get all personnages
     *
     * @return \Illuminate\Database\Eloquent\Collection|Personnage[]
     */
    public function index()
    {
        return Personnage::all();
    }

    /**
     * Get all personnages from an owner
     *
     * @param int $id
     * @return mixed
     */
    public function byOwner(int $id)
    {
        return Personnage::of($id)->active(true)->get();
    }

    /**
     * Show a personnage
     *
     * @param Personnage $personnage
     * @return Personnage
     */
    public function show(Personnage $personnage)
    {
        return $personnage;
    }

    /**
     * Create a personnage
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store()
    {
        $data = request()->only(["name", "bio", "signature", "aversions", "affections", "job", "title", "hide", "owner"]);

        try {
            $personnage = Personnage::create($data);

            /**
             * If there is an avatar, attach it to newly created personnage
             */
            if(request()->file("avatar")) {
                $personnage->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }

            return response()->json($personnage);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Update a personnage
     *
     * @param Personnage $personnage
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(Personnage $personnage)
    {
        $data = request()->only(["name", "bio", "signature", "aversions", "affections", "job", "title", "hide"]);

        try {
            $personnage->update($data);

            /**
             * If there is an avatar, attach it to newly created personnage
             */
            if(request()->file("avatar")) {
                $personnage->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }

            return response()->json($personnage);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Delete a personnage
     *
     * @param Personnage $personnage
     * @throws \Exception
     */
    public function destroy(Personnage $personnage)
    {
        try {
            $personnage->delete();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Activate personnage
     *
     * @param Personnage $personnage
     * @return Personnage
     * @throws \Exception
     */
    public function activate(Personnage $personnage)
    {
        try {
            $personnage->update(['active' => true]);

            event(new PersonnageActivated($personnage));
            return $personnage;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Deactivate personnage
     *
     * @param Personnage $personnage
     * @return Personnage
     * @throws \Exception
     */
    public function deactivate(Personnage $personnage)
    {
        try {
            $personnage->update(['active' => false]);

            event(new PersonnageDeactivated($personnage));
            return $personnage;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Kill a personnage
     *
     * @param Personnage $personnage
     * @return Personnage
     * @throws \Exception
     */
    public function kill(Personnage $personnage)
    {
        try {
            Personnage::unguard();
            $personnage->update(['alive' => false, 'active' => false]);
            Personnage::reguard();

            event(new PersonnageKilled($personnage));
            return $personnage;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Resurrect a personnage
     *
     * @param Personnage $personnage
     * @return Personnage
     * @throws \Exception
     */
    public function resurrect(Personnage $personnage)
    {
        try {
            Personnage::unguard();
            $personnage->update(['alive' => true, 'active' => false]);
            Personnage::reguard();

            event(new PersonnageResurrected($personnage));
            return $personnage;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}