<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
        public function index()
{
    $student = Auth::guard('student')->user();

    $books = Book::with(['category', 'location'])
        ->where('Status', 'Active')
        ->get()
        ->map(function ($book) {
            return [
                'id' => $book->BookID,           // Keep this for backwards compatibility
                'bookId' => $book->BookID,       // Add this explicit field
                'title' => $book->Title,
                'author' => $book->Researcher,
                'date' => $book->PublishDate,
                'status' => $book->ReservationCount >= $book->MaxReservations ? 'BOOK LIMIT REACHED' : 'AVAILABLE',
                'category' => $book->category ? $book->category->Categories : '',
                'year' => date('Y', strtotime($book->PublishDate)),
                'abstract' => $book->Abstract,
            ];
        });

        // Fetch categories
        $categories = DB::connection('sqlsrv')
            ->table('Books_Inventory.dbo.category')
            ->pluck('Categories');

        // Pass all data to the view
        return view('student.library', compact('books', 'student', 'categories'));
    }

public function search(Request $request)
{
    $title = $request->input('title');
    $category = $request->input('category');
    $year = $request->input('year');

    try {
        // Debug info
        info('Search params:', [
            'title' => $title,
            'category' => $category,
            'year' => $year
        ]);

        if (!empty($title)) {
            $query = "EXEC Books_Inventory.dbo.SearchBooksTitle @Keyword = ?";
            $params = [$title];
        } elseif (!empty($category)) {
            $query = "EXEC Books_Inventory.dbo.SearchBookCat @Category = ?";
            $params = [$category];
        } elseif (!empty($year)) {
            $query = "EXEC Books_Inventory.dbo.SearchBooksYear @Year = ?";
            $params = [$year];
        } else {
            $query = "EXEC Books_Inventory.dbo.SearchBooksTitle @Keyword = ?";
            $params = [''];
        }

        // Debug the query
        info('Executing query:', ['query' => $query, 'params' => $params]);

        $books = DB::connection('sqlsrv')->select($query, $params);

        // Debug the raw results
        info('Raw books data:', ['books' => $books]);

        $mappedBooks = collect($books)->map(function ($book) {
            return [
                'id' => $book->BookID ?? null, // Include BookID
                'title' => $book->Title ?? null,
                'author' => $book->Researcher ?? null,
                'date' => $book->PublishDate ?? null,
                'status' => ($book->ReservationCount ?? 0) >= ($book->MaxReservations ?? 1) ? 'BOOK LIMIT REACHED' : 'AVAILABLE',
                'category' => $book->Category ?? null,
                'year' => $book->PublishDate ? date('Y', strtotime($book->PublishDate)) : null,
                'abstract' => $book->Abstract ?? 'No abstract available',
            ];
        });

        return response()->json($mappedBooks);

    } catch (\Exception $e) {
        info('Search error:', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        return response()->json([
            'error' => true,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
