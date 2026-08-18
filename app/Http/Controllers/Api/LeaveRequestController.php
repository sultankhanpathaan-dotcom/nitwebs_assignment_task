<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\LeaveStoreRequest;
use Illuminate\Http\RedirectResponse;
Use App\Models\User;
Use App\Models\Leave;
use Exception;
use App\Mail\LeaveRequestRejected;
use App\Mail\LeaveRequestSubmitted;
use App\Mail\LeaveRequestApproved;
use Mail;
use App\Jobs\SendEmailReminderJob;


class LeaveRequestController extends Controller
{
    
    public function leave_request(LeaveStoreRequest $request) {

    	  try {
            $validatedData = $request->validated();

          $emp_check = User::where([['id',$request->employee_id],['role_id',1]])->first();

          $overlappingLeave = Leave::where('employee_id', $request->employee_id)
           ->where('start_date', '<=', $request->end_date)
           ->where('end_date', '>=', $request->start_date)
           ->exists();

           if ($overlappingLeave) {
           	   throw new Exception("You already have a leave application during this time.");
           }

            if (empty($emp_check)) {
            	throw new Exception("employee ID not found.");
            }else{
            	$leave = new Leave();
            	$leave->employee_id = $request->employee_id;
            	$leave->start_date = $request->start_date;
            	$leave->end_date = $request->end_date;
            	$leave->reason = $request->reason;
            	$leave->status = '0';
            	$leave->save();
            	Mail::to('sultan.khan.pathaan@gmail.com')->queue(new LeaveRequestSubmitted());
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'Data processed successfully.',
                'data'    => $leave
            ], 200);

        }catch (Exception $e) {
            return response()->json([
                'status'  => 'false',
                'message' => 'An unexpected error occurred.',
                'debug'   => $e->getMessage() // Optional: Remove or hide in production
            ], 422);
        }
    }

    public function approve_reject_leave(Request $request){
    	try {
            $leave_request_check = Leave::where([['id',$request->leave_id]])->first();

            if (empty($leave_request_check)) {
            	throw new Exception("Leave ID not found.");
            }
            if($leave_request_check->status == '1' || $leave_request_check->status == '2' ){
            	throw new Exception("Leave Already Approved and Rejected.");

            }
            $leave_request_check->status = $request->status;
            $leave_request_check->approval_time = now(); 
            if ($leave_request_check->save()) {
            	if ($leave_request_check->status == '1') {
            		Mail::to('sultan.khan.pathaan@gmail.com')->queue(new LeaveRequestApproved());
            	}else{
            		Mail::to('sultan.khan.pathaan@gmail.com')->queue(new LeaveRequestRejected());
            	}
            }
            return response()->json([
                'status'  => 'success',
                'message' => 'Data Updated successfully.',
                'data'    => $leave_request_check
            ], 200);

        }
        catch (Exception $e) {
            return response()->json([
                'status'  => 'false',
                'message' => 'An unexpected error occurred.',
                'debug'   => $e->getMessage() // Optional: Remove or hide in production
            ], 422);
        }
    }

    public function list_employee_leave_request(){             
    	$userdata = Leave::with('User')->orderBy('id', 'DESC')->get();
    	 return response()->json([
                'status'  => 'success',
                'message' => 'Data Fethced successfully.',
                'data'    => $userdata
            ], 200);
    }
}
