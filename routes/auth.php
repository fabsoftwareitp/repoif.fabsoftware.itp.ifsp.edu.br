<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\ChangePasswordController;

Route::get("/cadastro", [RegisteredUserController::class, "create"])
    ->middleware("guest")
    ->name("register");

Route::post("/cadastro", [RegisteredUserController::class, "store"])
    ->middleware("guest");


Route::get("/verificacao-email", [EmailVerificationPromptController::class, "__invoke"])
    ->middleware("auth")
    ->name("verification.notice");

Route::get("/verificacao-email/{id}/{hash}", [VerifyEmailController::class, "__invoke"])
    ->middleware(["auth", "signed", "throttle:6,1"])
    ->name("verification.verify");

Route::post("/email/notificacao-verificacao", [EmailVerificationNotificationController::class, "store"])
    ->middleware(["auth", "throttle:6,1"])
    ->name("verification.send");


Route::get("/login", [AuthenticatedSessionController::class, "create"])
    ->middleware("guest")
    ->name("login");

Route::post("/login", [AuthenticatedSessionController::class, "store"])
    ->middleware("guest");


Route::get("/recuperacao-senha", [PasswordResetLinkController::class, "create"])
    ->middleware("guest")
    ->name("password.request");

Route::post("/recuperacao-senha", [PasswordResetLinkController::class, "store"])
    ->middleware("guest")
    ->name("password.email");

Route::get("/redefinicao-senha/{token}", [NewPasswordController::class, "create"])
    ->middleware("guest")
    ->name("password.reset");

Route::post("/redefinicao-senha", [NewPasswordController::class, "store"])
    ->middleware("guest")
    ->name("password.update");


Route::get("/alteracao-senha", [ChangePasswordController::class, "create"])
    ->middleware("auth")
    ->name("password.change");

Route::post("/alteracao-senha", [ChangePasswordController::class, "store"])
    ->middleware("auth");


Route::post("/logout", [AuthenticatedSessionController::class, "destroy"])
    ->middleware("auth")
    ->name("logout");
