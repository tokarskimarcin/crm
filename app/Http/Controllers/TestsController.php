<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestsController extends Controller
{
    /* 
        Wyświetlanie widoku test dla użytkownika
    */
    public function testUserGet() {
        return view('tests.userTest');
    }

    /* 
        Przesłanie odpowiedzi przez użytkownika
    */
    public function testUserPost(Request $request) {
        
    }

    /* 
        Wyświetlenie wszystkich testów użytkownika
    */
    public function allUserTests() {
        return view('tests.allUserTests');
    }

    /* 
        Dodanie testu przez osobę testującą
    */

    public function addTestGet() {
        return view('tests.addTest');
    }

    /* 
        Zapis testu przez osobę testującą
    */
    public function addTestPost(Request $request) {

    }

    /* 
        Wyświetlenie wsyzstkic testów osoby testującej
    */
    public function showTestsGet() {
        return view('tests.showTest');
    }

    /* 
        Zmiana statusu testu (osoba testująca)
    */
    public function showTestsPost(Request $request) {

    }

    /* 
        Pogdląd testu + możliwość jego oceny
    */
    public function testCheckGet() {
        return view('tests.checkTest');
    }

    /* 
        Zapis oceny testu
    */
    public function testCheckPost(Request $request) {
        
    }

    /* 
        Sttatystyki testów dla osoby testującej
    */
    public function testsStatisticsGet() {
        return view('tests.testsStatistics');
    }

    /* 
        Wyświetlanie widoku dla panelu administarcyjnego testów
    */
    public function testsAdminPanelGet() {
        return view('tests.testsAdminPanel');
    }

    /* 
        Zapisywanie zmian (panel administatorski testów)
    */
    public function testsAdminPanelPost(Request $request) {

    }

    /* 
        Statystyki poszczególnych pracowników
    */
    public function employeeTestsStatisticsGet() {
        return view('tests.employeeStatistics');
    }

    /* 
        Statystyki poszczególnych oddziałów
    */
    public function departmentTestsStatisticsGet() {
        return view('tests.departmentStatistics');
    }

    /* 
        Statystyki poszczególnych testów
    */
    public function testStatisticsGet() {
        return view('tests.oneTestStatistics');
    }
}
