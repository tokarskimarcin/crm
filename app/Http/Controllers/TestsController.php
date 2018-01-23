<?php

namespace App\Http\Controllers;

use App\TemplateQuestion;
use App\TemplateUserTest;
use App\TestUsersQuestion;
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
use App\Department_info;

class TestsController extends Controller
{
    /* 
        Wyświetlanie widoku testu dla użytkownika
        Sprawdzane jest pole "user_answer" w tabeli user_questions
        Pytania wybierane są kolejno, tam gdzie "user_answer"  = null
    */
    public function testUserGet($id) {
        $test = UserTest::find($id);

        /**
         * Sprawdzenie czy istnieje test
         */
        if ($test == null) {
            return view('errors.404');
        }

        /**
         * Sprawdzenie czy test należy do użytkownika
         */
        if ($test->user_id != Auth::user()->id) {
            return view('errors.404');
        }

        /**
         * Sprawdzenie czy test jest aktywowany
         */
        if ($test->status < 2) {
            return view('errors.404');
        }

        /**
         * SPrawdzenie czy test został rozpoczęty, jęzeli nie, wrzucamy czas poczatkowy testu
         */
        if ($test->test_start == null) {
            $test->test_start = date('Y-m-d H:i:s');
            $test->save();
        }

        /**
         * Pobranie pierwszego z kolejnosci pytania bez odpowiedzi
         */
        $question = UserQuestion::where('test_id', '=', $id)
            ->whereNull('user_answer')
            ->first();

        /**
         * Zliczenie ilości pytań w teście
         */
        $question_count = $test->questions->count();

        /**
         * Pobranie numeru aktualnego pytania
         */
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
            // jezeli ilość pytań bez odpowiedzi jest rowna sumie pytań to pytaine jest pierwsze 
            $status = 1;
        } else if ($actual_count <= 0) {
            //jezeli ilosc pytan bez odpowiedzi jest mniejsza lub rowna 0 to pytanie jest ostatnie
            $status = 2;
        } else {
            //jezeli pytanie nie jest pierwsze lub ostatnie to pytanie jest środkowe (MR OBVIOUS)
            $status = 0;
        }

        /**
         * Jezeli pytanie nie istnieje (koniec testu)
         */
        if ($question == null) {

            /**
             * Jezeli status testu jest mniejszy niz 4 (status oceniony)
             * to pytanie ma status 3 (do oceny)
             */
            if ($test->status < 4) {
                $status = 3;
                $test->status = 3;
                /**
                 * Jezeli pytanie nie istnieje i nie ma daty zakończenia testu, ustalamy ją
                 */
                if ($test->date_stop == null) {
                    $test->test_stop = date('Y-m-d H:i:s');
                }
                $test->save();
            }
            /**
             * Sprawdzenie czy test jest oceniony
             */
            if ($test->status == 4) {
                $status = 3;
            }
        }

        /**
         * Pobranie treści pytania
         */
        if ($question != null) {
            //odejmujemy od czasu rozpoczęcia pytania czas na jego rozwiązanie i nadpisujemy 
            $testQuestion = TestQuestion::where('id', '=', $question->question_id)->get();
        } else {
            //zdefiniowanie zmiennej (coś w widoku się wykrzacza bez niej)
            $testQuestion = false;
        }

        /**
         * Sprawdzenie czy była podjęta próba odpowiedzi
         * Sprawdzenie czy pytanie zostało juz rozpoczęte (w przypadku odświerzenia strony)
         */
        if ($question != null && $question->user_answer == null && $question->attempt != null)
            $rest_of_time = $question->available_time - (strtotime($question->attempt) - time()) * (-1);
        else 
            $rest_of_time = false;

        return view('tests.userTest')
            ->with('test', $test)
            ->with('rest_of_time', $rest_of_time)
            ->with('testQuestion', $testQuestion[0])
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

    /**
     * Funkcja zwracająca widok z dostępem do wszystkich zakończonych testów
     */
    public function allTestsGet() {
        $tests = UserTest::whereIn('status', [3])->get();

        return view('tests.allTests')
            ->with('tests', $tests);
    }

    /**
     * Wyświetlanie wyników testu
     */
    public function testResult($id) {
        $test = UserTest::find($id);
        
        /**
         * Sprawdzenie czy istnieje test
         */
        if ($test == null) {
            return view('errors.404');
        }

        /**
         * Sprawdzenie czy test należy do pracownika kadry lub osoby testowanej
         */
        if ($test->user_id != Auth::user()->id && $test->cadre_id != Auth::user()->id) {
            return view('errors.404');
        }

        /**
         * sprawdzenie czy test został oceniony i osoba testowana może mieć do niego wgląd
         */
        if ($test->user_id == Auth::user()->id && $test->status != 4) {
            return view('errors.404');
        }

        return view('tests.testResult')
            ->with('test', $test);
    }

    /* 
        Wyświetlenie wszystkich testów użytkownika
    */
    public function allUserTests() {
        $tests = UserTest::where('user_id', '=', Auth::user()->id)->get();

        return view('tests.allUserTests')
            ->with('tests', $tests);
    }

    /* 
        Dodanie testu przez osobę testującą
    */

    public function addTestGet() {
        // pobranie wszystkich kategorii
        $categories = TestCategory::where('deleted','=',0)->get();
        // pobranie wszystkich pracowników kardy(pracujących)
        $cadre = User::where('status_work','=',1)
            ->whereNotin('user_type_id',[1,2])->orderBy('last_name')->get();
        $teplate = TemplateUserTest::where('deleted',0)->get();
        //generowanie widoku
        return view('tests.addTest')
            ->with('categories',$categories)
            ->with('users',$cadre)
            ->with('template',$teplate);
    }

    /*
     * Przygotowanie danych do datatable, związanych z pytaniami na konkretną kategorię Datatable
     */
    public function showQuestionDatatable(Request $request)
    {
        if($request->ajax())
        {
            $query = TestQuestion::where('category_id',$request->category_id)->get();
            return datatables($query)
                ->rawColumns(['content'])
                ->make(true);
        }
    }
    /*
     *  Zapisywanie testu
     */
    public function saveTestWithUser(Request $request)
    {
        if($request->ajax()){
            // wyłuskanie wszystkoch użytkowników
            for ($i=0;$i<count($request->id_user_tab);$i++) {
                $new_test = new UserTest();
                $new_test->cadre_id = Auth::user()->id;
                $new_test->user_id = $request->id_user_tab[$i];
                $new_test->status = 1;
                $new_test->template_id = $request->template_id;
                $new_test->name = $request->subject;
                $new_test->save();
                $id_test = $new_test->id;
                $question_array = $request->question_test_array;

                foreach ($question_array as $item) {
                    $new_user_question = new UserQuestion();
                    $new_user_question->test_id = $id_test;
                    $new_user_question->question_id = $item['id'];
                    $new_user_question->available_time = $item['time'] * 60;
                    $new_user_question->save();
                    $new_many_to_many = new TestUsersQuestion();
                    $new_many_to_many->user_question_id = $new_user_question->id;
                    $new_many_to_many->test_question_id = $item['id'];
                    $new_many_to_many->save();
                }
            }
            Session::put('message_ok', "Test został utworzony!");
            return 1;
        }
        return 0;
    }

    // edycja testu ( usunięcie i wgranie ponownie
    public function editTestWithUser(Request $request)
    {
        if($request->ajax()) {
            // Sekcja usuwania
            //usunięcie wszystkich pytań danego testu
            $test_id = $request->test_id;
            $status = UserTest::find($test_id);
            if ($status->status == 1) {

                $user_question = UserQuestion::where('test_id', $test_id)->get();
                foreach ($user_question as $item) {
                    // usuniecie z pytań do testu
                    TestUsersQuestion::where('user_question_id', '=', $item->id)->delete();
                }
                // usunięcie z pytań testu
                UserQuestion::where('test_id', $test_id)->delete();
                // usunięcie testu
                UserTest::where('id', '=', $test_id)->delete();

                for ($i = 0; $i < count($request->id_user); $i++) {
                    $new_test = new UserTest();
                    $new_test->cadre_id = Auth::user()->id;
                    $new_test->user_id = $request->id_user[$i];
                    $new_test->status = 1;
                    $new_test->template_id = $request->template_id;
                    $new_test->name = $request->subject;
                    $new_test->save();
                    $id_test = $new_test->id;
                    $question_array = $request->question_test_array;

                    foreach ($question_array as $item) {
                        $new_user_question = new UserQuestion();
                        $new_user_question->test_id = $id_test;
                        $new_user_question->question_id = $item['id'];
                        $new_user_question->available_time = $item['time'] * 60;
                        $new_user_question->save();
                        $new_many_to_many = new TestUsersQuestion();
                        $new_many_to_many->user_question_id = $new_user_question->id;
                        $new_many_to_many->test_question_id = $item['id'];
                        $new_many_to_many->save();
                    }
                }
            }
        }
            return 1;
    }
    // Wysłanie infromacji o użytkowniku, jakie pytania już rozwiązał
    public function getRepeatQuestion (Request $request)
    {
        if($request->ajax())
        {   // chwilowo tylko sprawdza czy rozwiązywał a nie ile razy to robił
            $user_question_repeat = DB::table('user_questions')
            ->select(DB::raw('
                Distinct(question_id)
            '))
                ->join('user_tests', 'user_tests.id', 'user_questions.test_id')
                ->join('users', 'users.id', 'user_tests.user_id')
                ->where('users.id',$request->id_user)
                ->get();
            return response()->json($user_question_repeat);
        }
    }

    /*
     * Podgląd testu
     */
    public function viewTest($id)
    {
        // pobranie informacji o teście
        $test_by_id = UserTest::find($id);
        if($test_by_id->status == 1) {
            // pobranie pytań z testu
            $all_question_id = $test_by_id->questions()->get();
            // pobranie wszystkich kategorii
            $categories = TestCategory::where('deleted', '=', 0)->get();
            // pobranie wszystkich pracowników kardy(pracujących)
            $cadre = User::where('status_work', '=', 1)
                ->whereNotin('user_type_id', [1, 2])->orderBy('last_name')->get();
            $teplate = TemplateUserTest::where('deleted', 0)->get();
            //generowanie widoku
            $all_question = array();
            foreach ($all_question_id as $item) {
                $content_question = $item->testQuestion()->get();
                $category_name = TestCategory::where('id', '=', $content_question[0]->category_id)->get();
                array_push($all_question, ["id_question" => $item->question_id, "content" => $content_question[0]->content, "category_name" => $category_name[0]->name, "avaible_time" => $item->available_time]);
            }

            return view('tests.viewTest')
                ->with('test_by_id', $test_by_id)
                ->with('all_question', $all_question)
                ->with('categories', $categories)
                ->with('users', $cadre)
                ->with('template', $teplate);
        }else{
            return redirect('show_tests');
        }
    }

    /*
     * Pobranie pytań do szablonu
     */
    public function getTemplateQuestion(Request $request)
    {
        if($request->ajax())
        {
            $question_id = TemplateQuestion::select('question_id','question_time',
                'test_questions.content','test_categories.name')
                ->join('test_questions','question_id','test_questions.id')
                ->join('test_categories','test_questions.category_id','test_categories.id')
                ->where('template_id',$request->template_id)
                ->get();
            return $question_id;
        }
        return 0;
    }

    /* 
        Zapis testu przez osobę testującą
    */
    public function addTestPost(Request $request) {

    }

    /*
        Dodawanie szablonu testu
    */
    public function addTestTemplate()
    {
        $categories = TestCategory::where('deleted','=',0)->get();
        $cadre = User::where('status_work','=',1)
            ->whereNotin('user_type_id',[1,2])->orderBy('last_name')->get();
        return view('tests.addTestTemplate')
            ->with('categories',$categories)
            ->with('users',$cadre);
    }

    /*
       Zapisywanie szablonu testu
   */
    public function saveTestTemplate(Request $request)
    {
        if($request->ajax())
        {
                $new_template = new TemplateUserTest();
                $new_template->template_name = $request->template;
                $new_template->cadre_id = Auth::user()->id;
                $new_template->name= $request->subject;
                $new_template->save();
                $id_template = $new_template->id;
                $question_array = $request->question_test_array;

                foreach ($question_array as $item)
                {
                    $new_template_question = new TemplateQuestion();
                    $new_template_question->template_id = $id_template;
                    $new_template_question->question_id = $item['id'];
                    $new_template_question->question_time = $item['time']*60;
                    $new_template_question->save();
                }
                return 1;
            }
            return 0;
    }

    /*
        Wyświetlenie wsyzstkic testów osoby testującej
    */
    public function showTestsGet() {
        $tests = UserTest::where('cadre_id', '=', Auth::user()->id)->get();
        return view('tests.showTest')
            ->with('tests', $tests);
    }

    /* 
        Zmiana statusu testu (osoba testująca)
    */
    public function showTestsPost(Request $request) {

    }

    /* 
        Ocena testu
    */
    public function testCheckGet($id) {
        $test = UserTest::find($id);

        if ($test == null) {
            return view('errors.404');
        }

        if ($test->status < 3) {
            return view('errors.404');
        }

        /*********************************
         * Tutaj na koniec dodac sprawdzenie czy uzytkownik nie sprawdza testu sam sobie
         ********************************/

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

        /**
         * Zdefiniowanie początkowej zmiennej przechowującej sumaryczny wynik testu
         */
        $result = 0;

        /**
         * Przekazanie danych na temat testu do tablic
         */
        $cadre_comments = $request->comment_question;
        $cadre_result = $request->question_result;

        /**
         * Zapis danych o pytaniach
         */
        foreach($test->questions as $question) {
            /**
             * Dodanie komentarza do pytania
             * Defaultowo 'Brak komentarza'
             */
            $question->cadre_comment = ($cadre_comments[0] != null) ? $cadre_comments[0] : 'Brak komentarza.' ;
            $question->result = ($cadre_result[0] != null) ? intval($cadre_result[0]) : 0 ;
            $question->save();
            /**
             * Dodanie wyniku pytania do sumarycznego wyniku testu
             */
            $result += intval($cadre_result[0]);
            /**
             * usunięcie pierwszych elementów tablicy z pytaniami
             */
            array_shift($cadre_comments);
            array_shift($cadre_result);
        }

        /**
         * Zmiana statusu testu na oceniony
         */
        $test->status = 4;
        /**
         * Zapis sumarycznego wyniku testu
         */
        $test->result = $result;

        /**
         * Zapis użytkownika sprawdzającego test
         */
        $test->checked_by = Auth::user()->id;

        $test->save();

        Session::flash('message_ok', "Ocena została przesłana!");
        return Redirect::back();
    }

    /* 
        Sttatystyki testów dla osoby testującej
    */
    public function testsStatisticsGet() {
        $tests = UserTest::all();

        $departments_stats = DB::table('user_tests')
            ->select(DB::raw('
                departments.name as dep_name,
                department_type.name as dep_type_name,
                count(user_tests.id) as dep_sum
            '))
            ->join('users', 'users.id', 'user_tests.user_id')
            ->join('department_info', 'users.department_info_id', 'department_info.id')
            ->join('departments', 'departments.id', 'department_info.id_dep')
            ->join('department_type', 'department_type.id', 'department_info.id_dep_type')
            ->groupBy('department_info.id')
            ->get();
            
        $stats_by_user_type = DB::table('user_tests')
            ->select(DB::raw('
                user_types.name as user_type,
                count(user_tests.id) as user_type_sum
            '))
            ->join('users', 'users.id', 'user_tests.user_id')
            ->join('user_types', 'users.user_type_id', 'user_types.id')
            ->get();

        $results = DB::table('user_questions')
            ->select(DB::raw('
                SUM(CASE WHEN result = 1 THEN 1 ELSE 0 END) as good,
                SUM(CASE WHEN result = 0 THEN 1 ELSE 0 END) as bad
            '))
            ->get();

        return view('tests.testsStatistics')
            ->with('results', $results[0])
            ->with('stats_by_user_type', $stats_by_user_type)
            ->with('departments_stats', $departments_stats)
            ->with('tests', $tests);
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
    public function employeeTestsStatisticsGet($id) {
        $user = User::find($id);

        if ($user == null) {
            return view('errors.404');
        }

        $cadre = DB::table('user_tests')
            ->select(DB::raw('
                first_name,
                last_name,
                count(*) as cadre_sum
            '))
            ->join('users', 'users.id', 'user_tests.cadre_id')
            ->where('user_tests.user_id', '=', $id)
            ->groupBy('users.id')
            ->get();

        $categories = DB::table('user_questions')
            ->select(DB::raw('
                test_categories.name as name,
                count(*) as sum
            '))
            ->join('user_tests', 'user_tests.id', 'user_questions.test_id')
            ->join('test_questions', 'test_questions.id', 'user_questions.question_id')
            ->join('test_categories', 'test_categories.id', 'test_questions.category_id')
            ->where('user_tests.user_id', '=', $id)
            ->groupBy('test_categories.id')
            ->get();

        $stats = DB::table('user_questions')
            ->select(DB::raw('
                sum(CASE WHEN user_questions.result = 1 THEN 1 else 0 END) as user_good,
                sum(CASE WHEN user_questions.result = 0 THEN 1 else 0 END) as user_wrong
            '))
            ->join('user_tests', 'user_tests.id', 'user_questions.test_id')
            ->where('user_tests.user_id', '=', $id)
            ->where('user_tests.result', '!=', null)
            ->get();

        return view('tests.employeeStatistics')
            ->with('stats', $stats[0])
            ->with('categories', $categories)
            ->with('cadre', $cadre)
            ->with('user', $user);
    }

    /* 
        Statystyki poszczególnych oddziałów
    */
    public function departmentTestsStatisticsGet() {
        $department_info = Department_info::all();

        return view('tests.departmentStatistics') 
            ->with('department_info', $department_info);
    }

    /**
     * Statystyk wybranego oddziału
     */

     public function departmentTestsStatisticsPost(Request $request) {
        $id = $request->dep_id;

        $department = Department_info::find($id);

        if ($department == null) {
            return view('errors.404');
        }

        $department_info = Department_info::all();

        /**
         * Pobranie ilości przeprowadznych testow w oddziale
         */
        $count_dep_test_sum = DB::table('user_tests')
            ->select(DB::raw('
                count(*) as dep_sum
            '))
            ->leftJoin('users', 'users.id', 'user_tests.user_id')
            ->where('users.department_info_id', '=', $id)
            ->get();

        /**
         * Pobranie ilości dobrych i złych odpowiedzi
         */
        $results = DB::table('user_questions')
            ->select(DB::raw('
                sum(CASE WHEN user_questions.result = 1 THEN 1 ELSE 0 END) as dep_good,
                sum(CASE WHEN user_questions.result = 0 THEN 1 ELSE 0 END) as dep_wrong
            '))
            ->join('user_tests', 'user_tests.id', 'user_questions.test_id')
            ->join('users', 'users.id', 'user_tests.user_id')
            ->where('user_tests.result', '!=', null)
            ->where('users.department_info_id', '=', $id)
            ->get();

        /**
         * Pobranie ilosci testow na uzytkownika
         */
        $tests_by_user = DB::table('user_tests')
            ->select(DB::raw('
                first_name,
                last_name,
                count(*) as user_sum
            '))
            ->leftJoin('users', 'users.id', 'user_tests.user_id')
            ->where('users.department_info_id', '=', $id)
            ->groupBy('users.id')
            ->get();

        /**
         * Pobranie ilosci wykonanych testow przez kadre
         */
        $tests_by_cadre = DB::table('user_tests')
            ->select(DB::raw('
                first_name,
                last_name,
                count(*) as user_sum
            '))
            ->leftJoin('users', 'users.id', 'user_tests.cadre_id')
            ->where('users.department_info_id', '=', $id)
            ->groupBy('users.id')
            ->get();

        /**
         * Pobranie ulości pytan ze względu na kategorię
         */
        $categories = DB::table('user_questions')
            ->select(DB::raw('
                test_categories.name as name,
                count(*) as sum
            '))
            ->join('user_tests', 'user_tests.id', 'user_questions.test_id')
            ->join('test_questions', 'test_questions.id', 'user_questions.question_id')
            ->join('test_categories', 'test_categories.id', 'test_questions.category_id')
            ->join('users', 'users.id', 'user_tests.user_id')
            ->where('users.department_info_id', '=', $id)
            ->groupBy('test_categories.id')
            ->get();

        return view('tests.departmentStatistics')
            ->with('results', $results[0])
            ->with('dep_sum', $count_dep_test_sum[0]->dep_sum)
            ->with('id', $id)
            ->with('tests_by_user', $tests_by_user)
            ->with('tests_by_cadre', $tests_by_cadre)
            ->with('categories', $categories)
            ->with('department_info', $department_info)
            ->with('department', $department);
     }

    /* 
        Statystyki poszczególnych testów
    */
    public function testStatisticsGet($id) {
        $test = TemplateUserTest::find($id);

        if ($test == null) {
            return view('errors.404');
        }

        /**
         * Funkcja zliczająca wyniki pracownikow  dla danego testu
         */

        $results = DB::table('user_questions')
            ->select(DB::raw('
                SUM(CASE WHEN user_questions.result is null THEN 1 ELSE 0 END) as not_judged,
                SUM(CASE WHEN user_questions.result = 1 THEN 1 ELSE 0 END) as good,
                SUM(CASE WHEN user_questions.result = 0 THEN 1 ELSE 0 END) as bad
            '))
            ->join('user_tests', 'user_tests.id', 'user_questions.test_id')
            ->where('user_tests.template_id', '=', $id)
            ->get();

        return view('tests.oneTestStatistics')
            ->with('results', $results[0])
            ->with('test', $test);
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
            $category->name = htmlentities($request->new_name_category, ENT_QUOTES, "UTF-8");
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

    function activateTest(Request $request) {
        if ($request->ajax()) {
            $checkTest = UserTest::find($request->id);

            if ($checkTest == null) {
                return 0;
            }

            $checkTest->status = 2;
            $checkTest->save();

            return 1;
        }
    }

    /**
     * Metoda zapisująca podjęcie próby rozwiązania zadania 
     * 
     * @param Request 
     * @author konradja100
     * @access Public 
     * @return void
     */
    public function testAttempt(Request $request) {
        if ($request->ajax()) {
            $question = UserQuestion::find($request->question_id);

            /**
             * Sprawdzenie czy pytanie istnieje w bazie 
             */
            if ($question == null) {
                return 0;
            }

            $question->attempt = date('Y-m-d H:i:s');
            $question->save();

            return 1;
        }
    }
}
