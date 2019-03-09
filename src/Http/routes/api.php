<?php
Route::get("owner/{id}/personnages", "PersonnageController@byOwner")->name('personnages.byOwner');
Route::get("owner/{id}/personnages", "PersonnageController@byOwnerActive")->name('personnages.active');
Route::apiResource("personnages", "PersonnageController");
Route::get("personnages/{personnage}/kill", "PersonnageController@kill");
Route::get("personnages/{personnage}/resurrect", "PersonnageController@resurrect");
Route::get("personnages/{personnage}/activate", "PersonnageController@activate");
Route::get("personnages/{personnage}/deactivate", "PersonnageController@deactivate");
Route::get("personnages/{personnage}/change", "PersonnageController@changeTo");