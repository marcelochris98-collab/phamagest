<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Facture #{{ $vente->id }} — PharmaGest</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #0f172a; }
        .header { border-bottom: 2px solid #059669; padding-bottom: 12px; margin-bottom: 24px; }
        .logo { font-size: 22px; font-weight: bold; color: #059669; }
        .meta { margin-top: 8px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #f1f5f9; text-align: left; padding: 8px; font-size: 11px; text-transform: uppercase; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; }
        .total { font-size: 16px; font-weight: bold; color: #059669; text-align: right; margin-top: 16px; }
        .footer { margin-top: 40px; font-size: 10px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">PharmaGest</div>
        <div class="meta">Import & revente pharmaceutique B2B — Douala, Cameroun</div>
    </div>

    <table style="width:100%; margin-bottom:20px;">
        <tr>
            <td style="width:50%; vertical-align:top;">
                <strong>FACTURE N° {{ str_pad($vente->id, 6, '0', STR_PAD_LEFT) }}</strong><br>
                Date : {{ $vente->date_vente->format('d/m/Y') }}
            </td>
            <td style="width:50%; vertical-align:top; text-align:right;">
                <strong>Client</strong><br>
                {{ $vente->client->nom }}<br>
                {{ $vente->client->type->label() }}<br>
                @if ($vente->client->adresse){{ $vente->client->adresse }}<br>@endif
                @if ($vente->client->telephone)Tél. {{ $vente->client->telephone }}@endif
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Médicament</th>
                <th>Qté</th>
                <th>P.U. (FCFA)</th>
                <th style="text-align:right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vente->items as $item)
                <tr>
                    <td>{{ $item->medicament->nom }} @if($item->medicament->dosage)({{ $item->medicament->dosage }})@endif</td>
                    <td>{{ $item->quantite }}</td>
                    <td>{{ number_format($item->prix_unitaire, 0, ',', ' ') }}</td>
                    <td style="text-align:right">{{ number_format($item->quantite * $item->prix_unitaire, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">TOTAL : {{ number_format($vente->total, 0, ',', ' ') }} FCFA</div>

    <p style="margin-top:24px; font-size:11px;">Vendeur : {{ $vente->user->name }}</p>

    <div class="footer">
        PharmaGest — Document généré le {{ now()->format('d/m/Y à H:i') }}
    </div>
</body>
</html>
