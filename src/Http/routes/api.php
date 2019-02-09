<?php
Route::get("owner/{id}/personnages", "PersonnageController@byOwner")->name('personnages.byOwner');
Route::apiResource("personnages", "PersonnageController");
Route::get("personnages/{personnage}/kill", "PersonnageController@kill")->name('personnages.kill');
Route::get("personnages/{personnage}/resurrect", "PersonnageController@resurrect")->name('personnages.resurrect');
Route::get("personnages/{personnage}/activate", "PersonnageController@activate")->name('personnages.activate');
Route::get("personnages/{personnage}/deactivate", "PersonnageController@deactivate")->name('personnages.deactivate');
Route::get("personnages/{personnage}/change", "PersonnageController@change")->name('personnages.change');