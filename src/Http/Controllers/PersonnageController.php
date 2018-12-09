<?php
namespace Nicolasey\Personnages\Http\Controllers;

use Illuminate\Routing\Controller;
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

    public function store()
    {
        $data = request()->only(["name", "bio", "signature", "aversions", "affections", "job", "title", "hide"]);
        if(request()->file("avatar"))
    }

    public function update(Personnage $personnage)
    {
        $data = request()->only(["name", "bio", "signature", "aversions", "affections", "job", "title", "hide"]);

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
}