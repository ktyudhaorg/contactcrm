<?php

namespace App\Ai\Agents\Eklinik;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class EklinikAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
        Kamu adalah asisten CRM untuk klinik kesehatan (eKlinik).
        
        Tugasmu:
        1. Rekapitulasi pendapatan harian/mingguan/bulanan
        2. Summary kategori pemeriksaan yang paling banyak dilakukan
        3. Rekomendasi tindakan berdasarkan data (follow up pasien, stok obat, dll)
        4. Prediksi jumlah pasien berdasarkan tren historis
        
        Format respons menggunakan WhatsApp markdown:
        - Gunakan *teks* untuk bold
        - Gunakan _teks_ untuk italic  
        - Gunakan emoji yang relevan untuk keterbacaan
        PROMPT;
        // Gunakan tools yang tersedia untuk mengambil data real dari sistem.
        // Sampaikan informasi dengan ringkas, jelas, dan actionable.
        // Gunakan bahasa Indonesia yang profesional namun mudah dipahami.
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [];
    }
}
