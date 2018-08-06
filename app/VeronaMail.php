<?php
/**
 * Created by PhpStorm.
 * User: shuwax
 * Date: 03.08.2018
 * Time: 09:04
 */

namespace App;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Mail;

/**
 * Class Send mail by Verona
 * Class VeronaMail
 * @package App
 */
class VeronaMail
{

    private $mail_path,$data,$mail_title,$default_users = null,$depTypeId = [1,2];

    /**
     * VeronaMail constructor.
     * @param $mail_path            - path to view
     * @param $data                 - data to view (in array type example: $data = [ 'start_date' => '2018-07-03' ]
     * @param $mail_title           - mail title
     * @param null $default_users   - if we need to send mail to specific group example (User::whereIn('id', $menager)->get())
     * @param array $depTypeId      - if we need send mail only to Telemarketing array[2] or Potwierdzanie array[1] default to all group array[1,2]
     */
    public function __construct($mail_path,$data,$mail_title,$default_users = null,$depTypeId = [1,2])
    {
        $this->mail_path        = $mail_path;
        $this->data             = $data;
        $this->mail_title       = $mail_title;
        $this->default_users    = $default_users;
        $this->depTypeId        = $depTypeId;
    }

    /**
     * @return mixed
     */
    public function getMailPath()
    {
        return $this->mail_path;
    }

    /**
     * @param mixed $mail_path
     */
    public function setMailPath($mail_path)
    {
        $this->mail_path = $mail_path;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getMailTitle()
    {
        return $this->mail_title;
    }

    /**
     * @param mixed $mail_title
     */
    public function setMailTitle($mail_title)
    {
        $this->mail_title = $mail_title;
    }

    /**
     * @return null
     */
    public function getDefaultUsers()
    {
        return $this->default_users;
    }

    /**
     * @param null $default_users
     */
    public function setDefaultUsers($default_users)
    {
        $this->default_users = $default_users;
    }

    /**
     * @return array
     */
    public function getDepTypeId(): array
    {
        return $this->depTypeId;
    }

    /**
     * @param array $depTypeId
     */
    public function setDepTypeId(array $depTypeId)
    {
        $this->depTypeId = $depTypeId;
    }

    /**
     * Return users to send mail
     * @param $mail_type2
     * @return null
     */
    private function getUserToSendMail($mail_type2) : Collection{
        if ($this->getDefaultUsers() !== null) {
            return $this->getDefaultUsers();
        } else {
            $depTypeId = $this->getDepTypeId();
            $accepted_users = DB::table('users')
                ->select(DB::raw('
            users.first_name,
            users.last_name,
            users.username,
            users.email_off
            '))
                ->join('privilage_relation', 'privilage_relation.user_type_id', '=', 'users.user_type_id')
                ->join('department_info','department_info.id','users.department_info_id')
                ->join('department_type','department_type.id','department_info.id_dep_type')
                ->join('links', 'privilage_relation.link_id', '=', 'links.id')
                ->where(function ($querry) use ($depTypeId) {
                    $querry ->whereIn('department_type.id',$depTypeId)
                        ->orwhere('users.user_type_id','=',3);
                })
                ->where('links.link', '=', $mail_type2)
                ->where('users.status_work', '=', 1)
                ->where('users.id', '!=', 4592) // tutaj szczesna
                ->get();

            $selectedUsers = DB::table('users')
                ->select(DB::raw('
            users.first_name,
            users.last_name,
            users.username,
            users.email_off
            '))
                ->join('privilage_user_relation', 'privilage_user_relation.user_id', '=', 'users.id')
                ->join('links', 'privilage_user_relation.link_id', 'links.id')
                ->where('links.link', '=', $mail_type2)
                ->get();
            $accepted_users = $accepted_users->merge($selectedUsers);

            $szczesny = new User();
            $szczesny->username = 'bartosz.szczesny@veronaconsulting.pl';
            $szczesny->first_name = 'Bartosz';
            $szczesny->last_name = 'Szczęsny';
            $accepted_users->push($szczesny);
            return $accepted_users;
        }
    }

    /**
     * Generate path to link(check privilages (link name))
     * @return string
     */
    private function generateLinkToView() : string {
        $mail_path_pom = $this->getMailPath();
        $mail_without_folder = explode(".",$this->getMailPath());
        $mail_path = $mail_without_folder[count($mail_without_folder)-1];
        $mail_path2 = ucfirst($mail_path);
        $mail_path2 = 'page' . $mail_path2;
        return $mail_path2;
    }

    /**
     * Function send mail
     * @return bool
     */
    public function sendMail() : bool {
        //route mail
        $mail_type_pom = $this->getMailPath();
        //route to page with mail (privilages);
        $mail_type2 = $this->generateLinkToView();
        //get users to send mail
        $accepted_users = $this->getUserToSendMail($mail_type2);

        $mail_type = $mail_type_pom;
        $mail_title = $this->getMailTitle();
        try{
            /* UWAGA !!! ODKOMENTOWANIE TEGO POWINNO ZACZĄC WYSYŁAĆ MAILE*/
            Mail::send('mail.' . $mail_type, $this->getData(), function($message) use ($accepted_users, $mail_title)
            {
                $message->from('noreply.verona@gmail.com', 'Verona Consulting');
                foreach($accepted_users as $user) {
                    if (filter_var($user->username, FILTER_VALIDATE_EMAIL)) {
                        $message->to($user->username, $user->first_name . ' ' . $user->last_name)->subject($mail_title);
                    }
                    if (filter_var($user->email_off, FILTER_VALIDATE_EMAIL)) {
                        $message->to($user->email_off, $user->first_name . ' ' . $user->last_name)->subject($mail_title);
                    }
                }
            });
            return true;
        }catch (\Exception $eExept){
            return false;
        }
    }
}