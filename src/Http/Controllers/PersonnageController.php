<?php
namespace Nicolasey\Personnage\Http\Controllers;

use Illuminate\Routing\Controller;
use Nicolasey\Personnage\Models\Personnage;
use DB;

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
        $data = request()->only(["name", "bio", "signature", "aversions", "affections", "job", "title", "hide", "owner", "current"]);

        try {
            $personnage = Personnage::create($data);

            /**
             * If there is an avatar, attach it to newly created personnage
             */
            if(request()->file("avatar")) {
                $personnage->addMediaFromRequest('avatar')->toMediaCollection('avatars');
            }

            $this->changeCurrentPersonnage($personnage);

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
        $data = request()->only(["name", "bio", "signature", "aversions", "affections", "job", "title", "hide", "current"]);

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
            return $personnage;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Change current personnage to given one for owner
     *
     * @param Personnage $personnage
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function change(Personnage $personnage)
    {
        $this->changeCurrentPersonnage($personnage);
        return response()->json($personnage);
    }

    /**
     * Change current personnage to given one
     *
     * @param Personnage $personnage
     * @throws \Exception
     */
    private function changeCurrentPersonnage(Personnage $personnage)
    {
        $this->setAllPersonnageNotCurrent($personnage);
        $this->setCurrentPersonnage($personnage);
    }

    /**
     * Set current personnage
     *
     * @param Personnage $personnage
     * @throws \Exception
     */
    private function setCurrentPersonnage(Personnage $personnage)
    {
        try {
            $personnage->current = true;
            $personnage->save();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * Set all personnage to not so current
     *
     * @param Personnage $personnage
     * @throws \Exception
     */
    private function setAllPersonnageNotCurrent(Personnage $personnage)
    {
        // Get owner object
        $owner =  config("personnage.owner.class");
        $owner = new $owner;
        $owner = $owner::findOrFail($personnage->owner);

        // Set not current to all its personnages
        try {
            DB::beginTransaction();
            foreach ($owner->personnages as $pj) {
                $pj->current = false;
                $pj->save();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();
    }
}