@extends('layouts.crm')

@section('content')
    <div class="lead-back-row">
        <a class="back-link" href="{{ $leadDetails['back_route'] }}">{{ $leadDetails['back_text'] }}</a>
        <span class="breadcrumb">{{ $leadDetails['breadcrumb_text'] }}</span>
    </div>

    <div class="crm-panel">
        <div class="lead-hero">
            <div class="hero-profile">
                <div class="hero-avatar">{{ $leadDetails['current_lead']['lead_avatar_text'] }}</div>
                <div class="hero-copy">
                    <h3>{{ $leadDetails['current_lead']['lead_name'] }}</h3>
                    <p>{{ $leadDetails['current_lead']['lead_subtitle'] }}</p>
                    <div class="hero-badges">
                        <span class="status-pill status-pill--{{ $leadDetails['current_lead']['lead_source_tone'] }}">{{ $leadDetails['current_lead']['lead_source'] }}</span>
                        <span class="status-pill status-pill--{{ $leadDetails['current_lead']['lead_status_tone'] }}">{{ $leadDetails['current_lead']['lead_status'] }}</span>
                    </div>
                </div>
            </div>

            <div class="hero-actions">
                <button class="secondary-button" type="button">Edit Lead</button>
                <button class="success-button" type="button">Assign Project</button>
                <button class="secondary-button" type="button">Add Followup</button>
                <button class="secondary-button" type="button">Upload Document</button>
            </div>
        </div>
    </div>

    <div class="crm-panel" style="margin-top: 18px">
        <h4 style="margin: 0; font-size: 18px">Overview</h4>
        <p class="helper-copy">Customer context, lead requirement, follow-ups, documents, and complete activity history are organized below.</p>

        <div class="detail-columns">
            <section class="detail-card">
                <h4>Customer Information</h4>
                <div class="field-list">
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Mobile</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['customer_mobile'] }}</span>
                        </div>
                        <div class="field-block">
                            <span class="field-label">Email</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['customer_email'] }}</span>
                        </div>
                    </div>
                    <div class="field-block full">
                        <span class="field-label">Company</span>
                        <span class="field-value">{{ $leadDetails['current_lead']['customer_company'] }}</span>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">City</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['customer_city'] }}</span>
                        </div>
                        <div class="field-block">
                            <span class="field-label">State</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['customer_state'] }}</span>
                        </div>
                    </div>
                    <div class="field-block full">
                        <span class="field-label">Country</span>
                        <span class="field-value">{{ $leadDetails['current_lead']['customer_country'] }}</span>
                    </div>
                </div>
            </section>

            <section class="detail-card">
                <h4>Requirement Details</h4>
                <div class="field-list">
                    <div class="field-block full">
                        <span class="field-label">Requirement Message</span>
                        <span class="field-value">{{ $leadDetails['current_lead']['requirement_message'] }}</span>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Product</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['requirement_product'] }}</span>
                        </div>
                        <div class="field-block">
                            <span class="field-label">Quantity</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['requirement_quantity'] }}</span>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Budget</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['requirement_budget'] }}</span>
                        </div>
                        <div class="field-block">
                            <span class="field-label">Delivery</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['requirement_delivery'] }}</span>
                        </div>
                    </div>
                    <div class="field-block full">
                        <span class="field-label">Category</span>
                        <span class="field-value">{{ $leadDetails['current_lead']['requirement_category'] }}</span>
                    </div>
                </div>
            </section>

            <section class="detail-card">
                <h4>Lead Information</h4>
                <div class="field-list">
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Lead ID</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['lead_id'] }}</span>
                        </div>
                        <div class="field-block">
                            <span class="field-label">Source</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['lead_source'] }}</span>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Status</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['lead_status'] }}</span>
                        </div>
                        <div class="field-block">
                            <span class="field-label">Assigned To</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['lead_assigned_to'] }}</span>
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-block">
                            <span class="field-label">Created On</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['lead_created_on'] }}</span>
                        </div>
                        <div class="field-block">
                            <span class="field-label">Inquiry Date</span>
                            <span class="field-value">{{ $leadDetails['current_lead']['lead_inquiry_date'] }}</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="detail-grid-two">
            <section class="detail-card">
                <h4>Follow Ups</h4>
                <div class="detail-list">
                    @foreach ($leadDetails['follow_ups'] as $followUp)
                        <article class="detail-item">
                            <h5>{{ $followUp['subject'] }}</h5>
                            <p>{{ $followUp['note'] }}</p>
                            <div class="detail-meta">
                                <span>{{ $followUp['due_on'] }}</span>
                                <span>{{ $followUp['owner'] }}</span>
                                <span class="status-pill status-pill--{{ $followUp['status_tone'] }}">{{ $followUp['status'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="detail-card">
                <h4>Notes</h4>
                <div class="detail-list">
                    @foreach ($leadDetails['notes'] as $note)
                        <article class="detail-item">
                            <p>{{ $note['note_text'] }}</p>
                            <div class="detail-meta">
                                <span>{{ $note['author'] }}</span>
                                <span>{{ $note['timestamp'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>

        <div class="detail-grid-two">
            <section class="detail-card">
                <h4>Documents</h4>
                <div class="detail-list">
                    @foreach ($leadDetails['documents'] as $document)
                        <article class="detail-item">
                            <div style="display:flex; align-items:center; justify-content:space-between; gap:10px">
                                <strong>{{ $document['file_name'] }}</strong>
                                <span class="status-pill status-pill--slate">{{ $document['file_type'] }}</span>
                            </div>
                            <div class="detail-meta">
                                <span>Uploaded {{ $document['uploaded_on'] }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="detail-card">
                <h4>Activity Timeline</h4>
                <div class="timeline">
                    @foreach ($leadDetails['timeline'] as $item)
                        <article class="timeline-item">
                            <div class="timeline-icon">{{ $item['icon_text'] }}</div>
                            <div>
                                <h5>{{ $item['title'] }}</h5>
                                <div class="stamp">{{ $item['timestamp'] }}</div>
                                <div class="copy">{{ $item['description'] }}</div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection
