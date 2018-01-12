<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\TestCategory;
use App\TestQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Session;
use App\UserTest;
use App\UserQuestion;

class TestsController extends Controller
{
    /* 
        Wyświetlanie widoku testu dla użytkownika
        Sprawdzane jest pole "user_answer" w tabeli user_questions
        Pytania wybierane są kolejno, tam gdzie "user_answer"  = null
    */
    public function testUserGet($id) {
        $test = UserTest::find($id);

        if ($test == null) {
            return view('errors.404');
        }

        if ($test->user_id != Auth::user()->id) {
            return view('errors.404');
        }

        $question = UserQuestion::where('test_id', '=', $id)
            ->whereNull('user_answer')
            ->first();

        $question_count = $test->questions->count();
        $actual_count = UserQuestion::where('test_id', '=', $id)
            ->whereNull('user_answer')
            ->count();

        /* 
            $status = określa czy pytanie jest pierwszym lub ostatnim
            0 - pytanie nie jest początkowe/ostatnie
            1 - pierwsze pytanie
            2 - ostanite pytanie 
            3 - test zakończony
        */
        if ($question_count == $actual_count) {
            $status = 1;
        } else if ($actual_count <= 0) {
            $status = 2;
        } else {
            $status = 0;
        }

        if ($question == null) {
            $status = 3;
            $test->status = 3;
            $test->save();
        }
        

        return view('tests.userTest')
            ->with('test', $test)
            ->with('status', $status)
            ->with('question_count', $question_count)
            ->with('actual_count', $question_count - $actual_count + 1)
            ->with('question', $question);
    }

    /* 
        Przesłanie odpowiedzi przez użytkownika
    */
    public function testUserPost(Request $request) {
        $question = UserQuestion::find($request->question_id);

        if ($question->test->user_id != Auth::user()->id) {
            return view('errors.404');
        }

        if ($question == null) {
            return view('errors.404');
        }

        $question->user_answer = ($request->user_answer != null) ? $request->user_answer : 'Brak odpowiedzi' ;
        $answer_time = $request->answer_time;
        $question->answer_time = $request->available_time - $answer_time * (-1);

        $question->save();

        return Redirect::back();
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
        $categories = TestCategory::where('deleted','=',0)->get();
        $cadre = User::where('status_work','=',1)
            ->whereNotin('user_type_id',[1,2])->get();
        return view('tests.addTest')
            ->with('categories',$categories)
            ->with('users',$cadre);
    }

    /*
     * Przygotowanie danych do datatable, związanych z pytaniami na konkretną kategorię
     */
    public function showQuestionDatatable(Request $request)
    {
        if($request->ajax())
        {
            $query = TestQuestion::where('category_id',$request->category_id)->get();
            return datatables($query)->make(true);
        }
    }
    /*
     *  Zapisywanie testu
     */
    public function saveTestWithUser(Request $request)
    {
        if($request->ajax()){
            $new_test = new UserTest();
            $new_test->cadre_id = Auth::user()->id;
            $new_test->user_id = $request->id_user;
            $new_test->status = 1;
            $new_test->template_id = 0;
            $new_test->name= $request->subject;
            $new_test->save();
            $id_test = $new_test->id;
            $question_array = $request->question_test_array;

            foreach ($question_array as $item)
            {
                print_R($item);
                $new_user_question = new UserQuestion();
                $new_user_question->test_id = $id_test;
                $new_user_question->question_id = $item['id'];
                $new_user_question->available_time = $item['time'];
                $new_user_question->save();

            }
//            print_R($request->question_test_array);
//            print_R($request->id_user);
            print_R($id_test);

        }
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
    public function testCheckGet($id) {
        $test = UserTest::find($id);

        if ($test == null) {
            return view('errors.404');
        }

        return view('tests.checkTest')
            ->with('test', $test);
    }

    /* 
        Zapis oceny testu
    */
    public function testCheckPost(Request $request) {
        $test = UserTest::find($request->test_id);
        
        if ($test == null) {
            return view('errors.404');
        }

        $cadre_comments = $request->comment_question;

        foreach($test->questions as $question) {
            $question->cadre_comment = $cadre_comments[0];
            $question->save();
            $cadre_comments = array_shift($cadre_comments);
        }
        
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
            $question = new TestQuestion();

            $question->content = $request->content;
            $question->default_time = $request->question_time * 60;
            $question->category_id = $request->category_id;
            $question->created_at = date('Y-m-d H:i:s');
            $question->updated_at = date('Y-m-d H:i:s');
            $question->cadre_by = Auth::user()->id;
            $question->user_id = Auth::user()->id;
            $question->deleted = 0;

            $question->save();

            return 1;
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
            $data[] = TestQuestion::where('category_id', '=', $request->category_id)->where('deleted', '=', 0)->get();

            return $data;
        }
    }

    public function editTestQuestion(Request $request) {
        if ($request->ajax()) {
            $question = TestQuestion::find($request->question_id);

            if ($question == null) {
                return 0;
            }

            $question->content = $request->question;
            $question->default_time = $request->newTime * 60;
            $question->updated_at = date('Y-m-d H:i:s');
            $question->cadre_by = Auth::user()->id;

            $question->save();

            return 1;
        }
    }

    public function deleteTestQuestion(Request $request) {
        if ($request->ajax()) {
            $question = TestQuestion::find($request->id);

            if ($question == null) {
                return 0;
            }

            $question->deleted = 1;
            $question->updated_at = date('Y-m-d H:i:s');
            $question->cadre_by = Auth::user()->id;

            $question->save();

            return 1;
        }
    }

    public function mainTableCounter(Request $request) {
        if ($request->ajax()) {
            $category = TestCategory::find($request->category_id);

            if ($category == null) {
                return null;
            }

            return $category->questions->where('deleted', '=', 0)->count();
        }
    }
}
