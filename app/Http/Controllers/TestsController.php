<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TestCategory;
use App\TestQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;

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
        $testCategory = TestCategory::all();

        return view('tests.testsAdminPanel')
            ->with('testCategory', $testCategory);
    }

    /* 
        Zapisywanie zmian (panel administatorski testów)
    */
    public function testsAdminPanelPost(Request $request) {
        $category = new TestCategory();

        $category->name = $request->category_name;
        $category->user_id = Auth::user()->id;
        $category->cadre_id = Auth::user()->id;
        $category->created_at = date('Y-m-d H:i:s');
        $category->updated_at = date('Y-m-d H:i:s');
        $category->deleted = 0;
        $category->save();

        Session::flash('message_ok', "Kategoria została dodana!");
        return Redirect::back();
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

    /* 
        ******************** AJAX REQUESTS ************************
    */

    public function addTestQuestion(Request $request) {
        if ($request->ajax()) {
            
        }
    }

    public function saveCategoryName(Request $request) {
        if ($request->ajax()) {
            $category = TestCategory::find($request->category_id);

            if ($category == null) {
                return 0;
            }
            $category->name = $request->new_name_category;
            $category->updated_at = date('Y-m-d H:i:s');
            $category->cadre_id = Auth::user()->id;
            $category->save();

            return 1;
        }
    }

    public function categoryStatusChange(Request $request) {
        if ($request->ajax()) {
            $category = TestCategory::find($request->category_id);

            if ($category == null) {
                return 0;
            }
            $category->deleted = $request->new_status;
            $category->updated_at = date('Y-m-d H:i:s');
            $category->cadre_id = Auth::user()->id;
            $category->save();

            return 1;
        }
    }

    public function showCategoryQuestions(Request $request) {
        if ($request->ajax()) {
            $data = [];

            $data[] = TestCategory::find($request->category_id);
            $data[] = TestQuestion::where('category_id', '=', $request->category_id)->get();

            return $data;
        }
    }
}
