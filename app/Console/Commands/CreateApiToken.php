<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-token 
                            {email : Kullanıcının email adresi}
                            {--name=crm-api : Token adı}
                            {--abilities=* : Token yetenekleri}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bir kullanıcı için API token oluşturur';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $tokenName = $this->option('name');
        $abilities = $this->option('abilities');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("Email adresi '{$email}' ile kullanıcı bulunamadı.");

            return Command::FAILURE;
        }

        // Token oluştur
        $token = $user->createToken($tokenName, $abilities ?: ['*']);

        $this->info('Token başarıyla oluşturuldu!');
        $this->newLine();
        $this->line("Kullanıcı: {$user->name} ({$user->email})");
        $this->line("Token Adı: {$tokenName}");
        $this->line("Token ID: {$token->accessToken->id}");
        $this->newLine();
        $this->warn("Aşağıdaki token'ı kopyalayın. Bu token sadece bir kez gösterilecektir:");
        $this->newLine();
        $this->line($token->plainTextToken, 'fg=green');
        $this->newLine();

        return Command::SUCCESS;
    }
}
