<?php

namespace App\Documents\Invoices;


use App\Models\File;
use App\Models\Order;

use App\Helpers\FileHelper;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;

use Sabberworm\CSS\Rule\Rule;
use Setting;

class OrderInvoiceDocument extends AbstractInvoiceDocument
{
    protected $_order;

    protected $_file;

    protected $_tags = [
        'provisional',
        'final',
    ];

    public function __construct(Order $order)
    {
        parent::__construct();

        $this->_order = $order;
    }

    protected function _fileExists($tag = '')
    {
        $relation = Str::camel($this->_getRelationName($tag));

        $file = $this->_order->$relation;

        if ($file && $file instanceof File) {
            $this->_file = $file;

            return true;
        }

        return false;
    }

    protected function _getRelationName($tag)
    {
        return "{$tag}_invoice";
    }

    protected function _getFile($tag)
    {
        if (!in_array($tag, $this->_tags))
            throw new \Error('Wrong tag');

        if (! $this->_fileExists($tag)) {
            $this->_file = $this->_makeFile($tag);
        }

        return $this->_file;
    }

    protected function _makeFile($tag = '')
    {
        $name = $this->_generateFile($tag);
        $this->_generatePrintVersion($name, $tag);

        $file =  new File([
            'name' => $name,
            'mime' => 'application/pdf',
            'original_name' => $name,
            'thumbnail' => null,
        ]);
        $file->entity()->associate($this->_order);
        $file->save();

        $rel_id = $this->_getRelationName($tag) . '_id';
        $this->_order->$rel_id = $file->id;
        $this->_order->save();

        return $file;
    }

    protected function _generateFile($tag = '')
    {
        $data = $this->_getSettingsDataForInvoice($this->_order);

        $pdf = $this->_pdfMaker->loadView('documents.pdf.invoice', [
            'order' => $this->_order,
            'data' => $data,
            'tag' => $tag,
        ]);

        $path = \Storage::path(FileHelper::storagePath($this->_order));
        $name = "{$tag}.pdf";

        if (! file_exists($path)) mkdir($path, 0777, true);

        $pdf->save($path . $name);

        return $name;
    }

    protected function _generatePrintVersion($name, $tag)
    {
        $data = $this->_getSettingsDataForInvoice($this->_order);
        $path = FileHelper::storagePath($this->_order);

        $view = view('documents.pdf.invoice-print', [
            'order' => $this->_order,
            'data' => $data,
            'tag' => $tag,
        ])->render();

        \Storage::put($path . $name . '.html', $view);
    }

    protected function _getSettingsDataForInvoice(Order $order)
    {
        $partner = User::find($order->manager_id);

        if ($partner && $partner->hasRole(Role::PARTNER)) {
            Setting::setExtraColumns(array(
                'user_id' => $partner->id
            ));
        } else {
            $id = User::whereHas('roles', function($q) {
                $q->whereName(Role::ADMIN);
            })->value('id');

            Setting::setExtraColumns(array(
                'user_id' => $id
            ));
        }

        $settings = setting()->all();

        return [
            "company_name" => $settings['company_name'] ?? '',
            "company_address" => $settings['company_address'] ?? '',
            "company_post_code" => $settings['company_post_code'] ?? '',
            "company_city" => $settings['company_city'] ?? '',
            "company_website" => $settings['company_website'] ?? '',
            "company_mail" => $settings['company_mail'] ?? '',
            "company_phone" => $settings['company_phone'] ?? '',
            "company_director" => $settings['company_director'] ?? '',
            "company_registered_by" => $settings['company_registered_by'] ?? '',
            "company_tax_number" => $settings['company_tax_number'] ?? '',
            "company_tin" => $settings['company_tin'] ?? '',
            "company_bank_name" => $settings['company_bank_name'] ?? '',
            "company_bank_account" => $settings['company_bank_account'] ?? '',
            "company_bank_blz" => $settings['company_bank_blz'] ?? '',
            "company_bank_iban" => $settings['company_bank_iban'] ?? '',
            "company_bank_bic" => $settings['company_bank_bic'] ?? '',
        ];
    }

    public function download($tag = '')
    {
        $file = $this->_getFile($tag);

        return response()->download($file->file_path);
    }

    public function print($tag = '')
    {
        $file = $this->_getFile($tag);
        $html = file_get_contents($file->file_path . '.html');

        return response($html);
    }

    public function link($tag = '')
    {
        $file = $this->_getFile($tag);

        return $file->file_url;
    }

    public function path($tag = '')
    {
        $file = $this->_getFile($tag);

        return $file->file_path;
    }

    public function attachment($tag = '')
    {
        $file = $this->_getFile($tag);

        return [
            'data' => file_get_contents($file->file_path),
            'name' => $file->name,
            'mime' => $file->mime,
        ];
    }
}
