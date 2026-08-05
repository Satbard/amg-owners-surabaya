<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GuestbookExport;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Guestbook;
use App\Models\GuestbookEntry;
use App\Models\GuestbookEvent;
use App\Models\GuestbookField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class GuestbookController extends Controller
{
    public function create(GuestbookEvent $guestbookEvent)
    {
        return view('admin.guestbooks.create', compact('guestbookEvent'));
    }

    public function store(Request $request, GuestbookEvent $guestbookEvent)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'fields' => 'nullable|array',
            'fields.label.*' => 'required|max:255',
            'fields.field_type.*' => 'nullable|in:text,textarea,number,date',
            'fields.is_required.*' => 'nullable|boolean',
            'fields.id.*' => 'nullable|integer',
        ]);

        $guestbook = $guestbookEvent->guestbooks()->create([
            'name' => $validated['name'],
        ]);

        $this->syncFields($guestbook, $request->input('fields', []));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Membuat guestbook: '.$guestbook->name.' untuk acara '.$guestbookEvent->title,
            'ip_address' => $request->ip(),
        ]);

        return redirect("/admin/guestbooks/{$guestbookEvent->id}/guestbooks/{$guestbook->id}")
            ->with('success', 'Guestbook berhasil dibuat.');
    }

    public function show(GuestbookEvent $guestbookEvent, Guestbook $guestbook)
    {
        abort_unless($guestbook->guestbook_event_id === $guestbookEvent->id, 404);

        $guestbook->load(['fields', 'entries']);

        $guestbooks = $guestbookEvent->guestbooks()
            ->orderBy('name')
            ->get();

        return view('admin.guestbooks.show', compact('guestbookEvent', 'guestbook', 'guestbooks'));
    }

    public function edit(GuestbookEvent $guestbookEvent, Guestbook $guestbook)
    {
        abort_unless($guestbook->guestbook_event_id === $guestbookEvent->id, 404);

        $guestbook->load('fields');

        return view('admin.guestbooks.edit', compact('guestbookEvent', 'guestbook'));
    }

    public function update(Request $request, GuestbookEvent $guestbookEvent, Guestbook $guestbook)
    {
        abort_unless($guestbook->guestbook_event_id === $guestbookEvent->id, 404);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'fields' => 'nullable|array',
            'fields.label.*' => 'required|max:255',
            'fields.field_type.*' => 'nullable|in:text,textarea,number,date',
            'fields.is_required.*' => 'nullable|boolean',
            'fields.id.*' => 'nullable|integer',
        ]);

        $guestbook->update(['name' => $validated['name']]);

        $this->syncFields($guestbook, $request->input('fields', []));

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengupdate guestbook: '.$guestbook->name.' (acara '.$guestbookEvent->title.')',
            'ip_address' => $request->ip(),
        ]);

        return redirect("/admin/guestbooks/{$guestbookEvent->id}/guestbooks/{$guestbook->id}")
            ->with('success', 'Guestbook berhasil diperbarui.');
    }

    public function destroy(GuestbookEvent $guestbookEvent, Guestbook $guestbook)
    {
        abort_unless($guestbook->guestbook_event_id === $guestbookEvent->id, 404);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus guestbook: '.$guestbook->name.' (acara '.$guestbookEvent->title.')',
            'ip_address' => request()->ip(),
        ]);

        $guestbook->delete();

        return redirect("/admin/guestbooks/{$guestbookEvent->id}")
            ->with('success', 'Guestbook berhasil dihapus.');
    }

    public function storeEntry(Request $request, GuestbookEvent $guestbookEvent, Guestbook $guestbook)
    {
        abort_unless($guestbook->guestbook_event_id === $guestbookEvent->id, 404);

        $rules = [];

        foreach ($guestbook->fields as $field) {
            $rule = $field->is_required ? 'required' : 'nullable';

            switch ($field->field_type) {
                case 'textarea':
                    $rule .= '|string|max:5000';
                    break;
                case 'number':
                    $rule .= '|numeric';
                    break;
                case 'date':
                    $rule .= '|date';
                    break;
                default:
                    $rule .= '|string|max:255';
            }

            $rules['values.'.$field->id] = $rule;
        }

        $validated = $request->validate($rules);

        $data = [];

        foreach ($guestbook->fields as $field) {
            $value = $validated['values'][$field->id] ?? null;

            if ($value !== null && $value !== '') {
                $data[$field->id] = $value;
            }
        }

        $guestbook->entries()->create([
            'data' => $data,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menambah entri guestbook: '.$guestbook->name.' (acara '.$guestbookEvent->title.')',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Entri guestbook berhasil ditambahkan.');
    }

    public function destroyEntry(GuestbookEvent $guestbookEvent, Guestbook $guestbook, GuestbookEntry $entry)
    {
        abort_unless($guestbook->guestbook_event_id === $guestbookEvent->id, 404);
        abort_unless($entry->guestbook_id === $guestbook->id, 404);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Menghapus entri guestbook: '.$guestbook->name.' (acara '.$guestbookEvent->title.')',
            'ip_address' => request()->ip(),
        ]);

        $entry->delete();

        return redirect()->back()->with('success', 'Entri guestbook berhasil dihapus.');
    }

    public function export(GuestbookEvent $guestbookEvent, Guestbook $guestbook)
    {
        abort_unless($guestbook->guestbook_event_id === $guestbookEvent->id, 404);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Export guestbook: '.$guestbook->name.' (acara '.$guestbookEvent->title.')',
            'ip_address' => request()->ip(),
        ]);

        return Excel::download(
            new GuestbookExport($guestbook),
            'guestbook-'.Str::slug($guestbook->name).'.xlsx'
        );
    }

    /**
     * Sinkronkan kolom isian Guestbook.
     *
     * Field dikirim dengan struktur per-baris:
     *   fields[id][rowKey], fields[label][rowKey], fields[field_type][rowKey],
     *   fields[is_required][rowKey]
     *
     * Field dengan id lama di-update (nilai entri lama tetap valid),
     * field baru dibuat, field yang dihapus admin dibuang.
     */
    protected function syncFields(Guestbook $guestbook, array $fields)
    {
        $ids = $fields['id'] ?? [];
        $labels = $fields['label'] ?? [];
        $types = $fields['field_type'] ?? [];
        $isRequired = $fields['is_required'] ?? [];

        $submittedIds = [];

        $sort = 0;

        foreach ($labels as $rowKey => $label) {
            $id = isset($ids[$rowKey]) && $ids[$rowKey] !== ''
                ? (int) $ids[$rowKey]
                : null;

            $type = $types[$rowKey] ?? 'text';
            $required = isset($isRequired[$rowKey]) ? (bool) $isRequired[$rowKey] : false;

            $data = [
                'label' => $label,
                'field_type' => $type,
                'is_required' => $required,
                'sort_order' => $sort++,
            ];

            if ($id) {
                $submittedIds[] = $id;

                GuestbookField::where('id', $id)
                    ->where('guestbook_id', $guestbook->id)
                    ->update($data);
            } else {
                $field = $guestbook->fields()->create($data);
                $submittedIds[] = $field->id;
            }
        }

        GuestbookField::where('guestbook_id', $guestbook->id)
            ->whereNotIn('id', $submittedIds)
            ->delete();
    }
}
